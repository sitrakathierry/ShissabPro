<?php

namespace PdfBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\ModelePdf;
use AppBundle\Entity\PdfAgence;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PdfBundle:Default:index.html.twig');
    }

    public function addAction()
    {
    	$permission_user = $this->get('app.permission_user');
        $user = $this->getUser();
        $permissions = $permission_user->getPermissions($user);

        return $this->render('PdfBundle:Default:add.html.twig',array(
        	'permissions' => $permissions
        ));
    }

    public function saveAction(Request $request)
    {
        $id = $request->request->get('id');
        $nom = $request->request->get('nom');
        $type_modele = $request->request->get('type_modele');
        $logo_gauche_img = $request->request->get('logo_gauche_img');
        $logo_centre_img = $request->request->get('logo_centre_img');
        $logo_droite_img = $request->request->get('logo_droite_img');
        $texte_haut = $request->request->get('texte_haut');

        if ($id) {
            $modelePdf = $this->getDoctrine()
                ->getRepository('AppBundle:ModelePdf')
                ->find($id);
        } else{
            $modelePdf = new ModelePdf();
        }

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $modelePdf->setNom($nom);
        $modelePdf->setModele($type_modele);
        $modelePdf->setLogoDroite($logo_droite_img);
        $modelePdf->setLogoCentre($logo_centre_img);
        $modelePdf->setLogoGauche($logo_gauche_img);
        $modelePdf->setTexteHaut($texte_haut);
        $modelePdf->setAgence($agence);

        $em = $this->getDoctrine()->getManager();
        $em->persist($modelePdf);
        $em->flush();

        return new JsonResponse(array(
            'id' => $modelePdf->getId()
        ));
    }

    public function consultationAction()
    {
        $agences  = $this->getDoctrine()
                        ->getRepository('AppBundle:Agence')
                        ->findAll();

        $permission_user = $this->get('app.permission_user');
        $user = $this->getUser();
        $permissions = $permission_user->getPermissions($user);

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        return $this->render('PdfBundle:Default:consultation.html.twig',array(
            'agences' => $agences,
            'userAgence' => $userAgence,
        ));
    }

    public function listAction(Request $request)
    {

        $agence = $request->request->get('agence');
        $recherche_par = $request->request->get('recherche_par');
        $a_rechercher = $request->request->get('a_rechercher');

        $modeles  = $this->getDoctrine()
                        ->getRepository('AppBundle:ModelePdf')
                        ->getList(
                            $agence,
                            $recherche_par,
                            $a_rechercher
                        );

        return new JsonResponse($modeles);
    }

    public function showAction($id)
    {
        $modelePdf  = $this->getDoctrine()
                        ->getRepository('AppBundle:ModelePdf')
                        ->find($id);

        return $this->render('PdfBundle:Default:show.html.twig',array(
            'modelePdf' => $modelePdf,
        ));

    }

    public function pdfAction($id)
    {
        $modelePdf = $this->getDoctrine()
            ->getRepository('AppBundle:ModelePdf')
            ->find($id);

        $template = $this->renderView('PdfBundle:Default:pdf.html.twig',array(
            'modelePdf' => $modelePdf,
        ));

        $html2pdf = $this->get('app.html2pdf');

        $html2pdf->create();

        return $html2pdf->generatePdf($template, "modele-pdf" . $modelePdf->getId());
    }

    public function attributionAction()
    {

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $modeles = $this->getDoctrine()
                ->getRepository('AppBundle:ModelePdf')
                ->findBy(array(
                    'agence' => $agence
                ));

        $pdfAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:PdfAgence')
                    ->findBy(array(
                        'agence' => $agence
                    ));

        return $this->render('PdfBundle:Default:attribution.html.twig',array(
            'modeles' => $modeles,
            'pdfAgence' => $pdfAgence
        ));
    }

    public function saveAttributionAction(Request $request)
    {
        //$id = $request->request->get('id');
        //$facture = $request->request->get('facture');
        //$produit = $request->request->get('produit');
        $datas = $request->request->get('datas');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        foreach ($datas as $key => $data) {  
            $id = $data['id'];
            if ($id) {
                $pdfAgence = $this->getDoctrine()
                        ->getRepository('AppBundle:PdfAgence')
                        ->find($id);
            } else {
                $pdfAgence = new PdfAgence();
            }
            $modele = $this->getDoctrine()
                            ->getRepository('AppBundle:ModelePdf')
                            ->find($data['value']);

            //var_dump($modele);die;

            if($data['type'] == 0)
                $pdfAgence->setFacture($modele);
            else
                $pdfAgence->setProduit($modele);
            $pdfAgence->setAgence($agence);


            $em = $this->getDoctrine()->getManager();
            $em->persist($pdfAgence);
            $em->flush();
        }

        return new JsonResponse(array(
            'id' => $pdfAgence->getId()
        ));
    }
}
