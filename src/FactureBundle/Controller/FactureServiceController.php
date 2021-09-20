<?php

namespace FactureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Facture;
use AppBundle\Entity\FactureService;
use AppBundle\Entity\FactureServiceDetails;
use Symfony\Component\HttpFoundation\JsonResponse;

class FactureServiceController extends Controller
{
    public function saveAction(Request $request)
    {
        $f_type = $request->request->get('f_type');
        $f_model = $request->request->get('f_model');
        $f_client = $request->request->get('f_client');
        $f_date = $request->request->get('f_date');
        $descr = $request->request->get('descr');

        $montant = $request->request->get('service_montant');
        $f_remise = $request->request->get('f_service_remise');
        $remise = $request->request->get('service_remise');
        $total = $request->request->get('service_total');
        $somme = $request->request->get('service_somme');

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

             $factureService = $this->getDoctrine()
                ->getRepository('AppBundle:FactureService')
                ->findOneBy(array(
                    'facture' => $facture
                ));
        } else{
            $facture = new Facture();
            $newNum = $this->prepareNewNumFacture($agence->getId());
            $facture->setNum(intval($newNum));

            $factureService = new FactureService();

        }

        $facture->setType($f_type);
        $facture->setModele($f_model);
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

        $factureService->setFacture($facture);

        $em->persist($factureService);
        $em->flush();

        $details_list = explode(",", $list_id);

        // Suppression de tous les details
        foreach ($details_list as $old_id) {

            if ($old_id != "") {
                $old_detail = $this->getDoctrine()
                                    ->getRepository('AppBundle:FactureServiceDetails')
                                    ->find($old_id);

                $em->remove($old_detail);
                $em->flush();
            }

        }

        $f_service_libre = $request->request->get('f_service_libre');
        $f_service_designation = $request->request->get('f_service_designation');
        $f_service = $request->request->get('f_service');
        $f_service_periode = $request->request->get('f_service_periode');
        $f_service_duree = $request->request->get('f_service_duree');
        $f_service_prix = $request->request->get('f_service_prix');
        $f_service_montant = $request->request->get('f_service_montant');

        if (!empty($f_service)) {
            foreach ($f_service as $key => $value) {
                $detail = new FactureServiceDetails();

                $libre = $f_service_libre[$key];
                $designation = $f_service_designation[$key];
                $periode = $f_service_periode[$key];
                $duree = $f_service_duree[$key];
                $prix = $f_service_prix[$key];
                $montant = $f_service_montant[$key];

                if ($libre == 1) {
                    $detail->setDesignation($designation);
                    
                } else {
                    $service = $this->getDoctrine()
                        ->getRepository('AppBundle:Service')
                        ->find( $f_service[$key] );
                    $detail->setService($service);
                }

                $detail->setLibre($libre);
                $detail->setPeriode($periode ? $periode : '0.00');
                $detail->setDuree($duree);
                $detail->setPrix($prix);
                $detail->setMontant($montant);
                $detail->setFactureService($factureService);

                $em->persist($detail);
                $em->flush();

            }
        }

        return $this->redirectToRoute('facture_service_show',array('id' => $facture->getId()));

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

        $factureService  = $this->getDoctrine()
                        ->getRepository('AppBundle:FactureService')
                        ->findOneBy(array(
                        	'facture' => $facture
                        ));

        $definitif = $this->getDoctrine()
                        ->getRepository('AppBundle:Facture')
                        ->findOneBy(array(
                            'proforma' => $facture
                        ));

        $details = $this->getDoctrine()
                    ->getRepository('AppBundle:FactureServiceDetails')
                    ->findBy(array(
                        'factureService' => $factureService
                    ));

        $permission_user = $this->get('app.permission_user');
        $user = $this->getUser();
        $permissions = $permission_user->getPermissions($user);

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $clients = $this->getDoctrine()
            ->getRepository('AppBundle:Client')
            ->findBy(array(
                'agence' => $agence
            ));

        $services = $this->getDoctrine()
            ->getRepository('AppBundle:Service')
            ->findBy(array(
                'agence' => $agence
            ));

        $print = false;

        $pdfAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:PdfAgence')
                    ->findBy(array(
                        'agence' => $agence
                    ));
                    
        if (count($pdfAgence) > 0) {
            foreach ($pdfAgence as $key => $value) {
                if($value->getFacture()){
                    $print = true;
                }
            } 
        }


        return $this->render('FactureBundle:FactureService:show.html.twig', array(
            'facture' => $facture,
            'factureService' => $factureService,
            'details' => $details,
            'services' => $services,
            'clients' => $clients,
            'permissions' => $permissions,
            'print' => $print,
            'definitif' => $definitif,
        ));

    }

    public function pdfAction($id)
    {
        $facture  = $this->getDoctrine()
                        ->getRepository('AppBundle:Facture')
                        ->find($id);

        $factureService  = $this->getDoctrine()
                        ->getRepository('AppBundle:FactureService')
                        ->findOneBy(array(
                            'facture' => $facture
                        ));

        $details = $this->getDoctrine()
                    ->getRepository('AppBundle:FactureServiceDetails')
                    ->findBy(array(
                        'factureService' => $factureService
                    ));


        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $services = $this->getDoctrine()
            ->getRepository('AppBundle:Service')
            ->findBy(array(
                'agence' => $agence
            ));
            
        $pdfAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:PdfAgence')
                    ->findOneBy(array(
                        'agence' => $agence
                    ));       

        $modelePdf = null;
        if ($pdfAgence && $pdfAgence->getFacture()) {
           $modelePdf = $pdfAgence->getFacture();
        }

        $template = $this->renderView('FactureBundle:FactureService:pdf.html.twig', array(
            'facture' => $facture,
            'factureService' => $factureService,
            'details' => $details,
            'services' => $services,
            'modelePdf' => $modelePdf,
        ));

        $html2pdf = $this->get('app.html2pdf');

        $html2pdf->create();

        return $html2pdf->generatePdf($template, "facture" . $facture->getId());

    }
}
