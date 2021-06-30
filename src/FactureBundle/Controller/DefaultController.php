<?php

namespace FactureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Facture;
use AppBundle\Entity\FactureDetails;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('FactureBundle:Default:index.html.twig');
    }

    public function addAction()
    {

    	$permission_user = $this->get('app.permission_user');
        $user = $this->getUser();
        $permissions = $permission_user->getPermissions($user);

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

    	$clients = $this->getDoctrine()
            ->getRepository('AppBundle:Client')
            ->findAll();

        return $this->render('FactureBundle:Default:add.html.twig',array(
        	'clients' => $clients,
            'permissions' => $permissions,
            'userAgence' => $userAgence,
        ));
    }

    public function saveAction(Request $request)
    {
        $f_type = $request->request->get('f_type');
        $f_client = $request->request->get('f_client');
        $f_date = $request->request->get('f_date');
        $descr = $request->request->get('descr');

        $montant = $request->request->get('montant');
        $f_remise = $request->request->get('f_remise');
        $remise = $request->request->get('remise');
        $total = $request->request->get('total');
        $somme = $request->request->get('somme');

        $f_id = $request->request->get('f_id');
        $list_id = $request->request->get('list_id');

        $client = $this->getDoctrine()
            ->getRepository('AppBundle:Client')
            ->find($f_client);

        $agence = $client->getAgence();

        if ($f_id) {
            $facture = $this->getDoctrine()
                ->getRepository('AppBundle:Facture')
                ->find($f_id);
             $newNum = str_pad($facture->getNum(), 3, '0', STR_PAD_LEFT);
        } else{
            $facture = new Facture();
            $newNum = $this->prepareNewNumFacture($agence->getId());
            $facture->setNum(intval($newNum));
        }

        $facture->setType($f_type);
        $facture->setMontant($montant);
        $facture->setRemisePourcentage($f_remise);
        $facture->setRemiseValeur($remise);
        $facture->setTotal($total);
        $facture->setSomme($somme);
        $facture->setDescr($descr);


        
        $facture->setClient($client);

        $dateCreation = new \DateTime('now');
        $facture->setDateCreation($dateCreation);
        
        $date = \DateTime::createFromFormat('j/m/Y', $f_date);

        $facture->setDate($date);

        $facture->setAgence($agence);

        $em = $this->getDoctrine()->getManager();
        $em->persist($facture);
        $em->flush();

        $details_list = explode(",", $list_id);

        // Suppression de tous les details
        foreach ($details_list as $old_id) {

            if ($old_id != "") {
                $old_detail = $this->getDoctrine()
                                    ->getRepository('AppBundle:FactureDetails')
                                    ->find($old_id);

                $em->remove($old_detail);
                $em->flush();
            }

        }

        $f_designation = $request->request->get('f_designation');
        $f_prix = $request->request->get('f_prix');
        $f_montant = $request->request->get('f_montant');

        if (!empty($f_designation)) {
            foreach ($f_designation as $key => $value) {
                $detail = new FactureDetails();
                $designation = $f_designation[$key];
                $prix = $f_prix[$key];
                $montant = $f_montant[$key];

                $detail->setDesignation($designation);
                $detail->setPrix($prix);
                $detail->setMontant($montant);
                $detail->setFacture($facture);

                $em->persist($detail);
                $em->flush();

            }
        }

        return $this->redirectToRoute('facture_show',array('id' => $facture->getId()));

    }

    public function prepareNewNumFacture($id_agence)
    {
        $em = $this->getDoctrine()
                ->getManager();
        $newNum = $em
            ->getRepository("AppBundle:Facture")
            ->newNum($id_agence);

        return $newNum;

    }

    public function showAction($id)
    {
        $facture  = $this->getDoctrine()
                        ->getRepository('AppBundle:Facture')
                        ->find($id);

        $clients = $this->getDoctrine()
                ->getRepository('AppBundle:Client')
                ->findAll();

        $details = $this->getDoctrine()
                    ->getRepository('AppBundle:FactureDetails')
                    ->findBy(array(
                        'facture' => $facture
                    ));

        $permission_user = $this->get('app.permission_user');
        $user = $this->getUser();
        $permissions = $permission_user->getPermissions($user);

        return $this->render('FactureBundle:Default:show.html.twig', array(
            'facture' => $facture,
            'clients' => $clients,
            'details' => $details,
            'permissions' => $permissions,
        ));

    }

    public function pdfAction($id)
    {
        $facture  = $this->getDoctrine()
                        ->getRepository('AppBundle:Facture')
                        ->find($id);

        $details = $this->getDoctrine()
                    ->getRepository('AppBundle:FactureDetails')
                    ->findBy(array(
                        'facture' => $facture
                    ));

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $pdfAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:PdfAgence')
                    ->findOneBy(array(
                        'agence' => $agence
                    ));

        $modelePdf = $pdfAgence->getFacture();

        $template = $this->renderView('FactureBundle:Default:pdf.html.twig', array(
            'facture' => $facture,
            'details' => $details,
            'modelePdf' => $modelePdf,
        ));

        $html2pdf = $this->get('app.html2pdf');

        $html2pdf->create();

        return $html2pdf->generatePdf($template, "facture" . $facture->getId());

    }

    public function consultationAction()
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        return $this->render('FactureBundle:Default:consultation.html.twig',array(
            'agence' => $agence
        ));
    }

    public function listAction(Request $request)
    {
        $recherche_par = $request->request->get('recherche_par');
        $a_rechercher = $request->request->get('a_rechercher');
        $type_date = $request->request->get('type_date');
        $mois = $request->request->get('mois');
        $annee = $request->request->get('annee');
        $date_specifique = $request->request->get('date_specifique');
        $debut_date = $request->request->get('debut_date');
        $fin_date = $request->request->get('fin_date');
        $par_agence = $request->request->get('par_agence');

        $factures = $this->getDoctrine()
            ->getRepository('AppBundle:Facture')
            ->consultation(
                $recherche_par,
                $a_rechercher,
                $type_date,
                $mois,
                $annee,
                $date_specifique,
                $debut_date,
                $fin_date,
                $par_agence
            );

        return new JsonResponse($factures);
    }

}
