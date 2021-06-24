<?php

namespace PdfBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\ModelePdf;
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
        $modeles  = $this->getDoctrine()
                        ->getRepository('AppBundle:ModelePdf')
                        ->list();

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

        $img_base64_encoded = $modelePdf->getLogoDroite();
        $imageContent = file_get_contents($img_base64_encoded);
        $path = tempnam(sys_get_temp_dir(), 'prefix');

        file_put_contents ($path, $imageContent);

        $img = '<img src="' . $path . '">';

        $template = $this->renderView('PdfBundle:Default:pdf.html.twig',array(
            'modelePdf' => $modelePdf,
            'path' => $path,
        ));

        $html2pdf = $this->get('app.html2pdf');

        $html2pdf->create();

        return $html2pdf->generatePdf($template, "modele-pdf" . $modelePdf->getId());
    }

    public function attributionAction()
    {
        return $this->render('PdfBundle:Default:attribution.html.twig');
    }
}
