<?php

namespace FactureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Facture;
use AppBundle\Entity\FactureService;
use AppBundle\Entity\FactureServiceDetails;
use AppBundle\Entity\BonCommande;
use AppBundle\Entity\PannierBon;
use AppBundle\Entity\Credit;
use AppBundle\Entity\CreditDetails;
use Symfony\Component\HttpFoundation\JsonResponse;
use FactureBundle\Controller\BaseController;

class FactureServiceController extends BaseController
{
    public function saveAction(Request $request)
    {
        $f_type = $request->request->get('f_type');
        $f_model = $request->request->get('f_model');
        $f_client = $request->request->get('f_client');
        $f_date = $request->request->get('f_date');
        $f_lieu = $request->request->get('f_lieu');
        $descr = $request->request->get('descr');

        $montant = $request->request->get('service_montant');
        $f_remise = $request->request->get('f_service_remise');
        $remise = $request->request->get('service_remise');
        $f_remise_type = $request->request->get('f_service_remise_type');
        $total = $request->request->get('service_total');
        $somme = $request->request->get('somme_service');
        $f_is_credit = $request->request->get('f_is_credit');
        $f_service_devise = $request->request->get('f_service_devise');
        $f_service_montant_converti = $request->request->get('f_service_montant_converti');

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
        $facture->setRemiseType($f_remise_type);
        $facture->setRemisePourcentage($f_remise);
        $facture->setRemiseValeur($remise);
        $facture->setTotal($total);
        $facture->setSomme($somme);
        $facture->setDescr($descr);
        $facture->setClient($client);
        $facture->setIsCredit($f_is_credit);

        if ($f_service_devise) {
            $devise = $this->getDoctrine()
                ->getRepository('AppBundle:Devise')
                ->find($f_service_devise);
            
            $facture->setDevise($devise);
            $facture->setMontantConverti($f_service_montant_converti);
        }


        $dateCreation = new \DateTime('now');
        $facture->setDateCreation($dateCreation);
        
        $date = \DateTime::createFromFormat('j/m/Y', $f_date);

        $facture->setDate($date);
        $facture->setLieu($f_lieu);

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
        $f_service_remise_type_ligne = $request->request->get('f_service_remise_type_ligne');
        $f_service_remise_ligne = $request->request->get('f_service_remise_ligne');

        if (!empty($f_service)) {
            foreach ($f_service as $key => $value) {
                $detail = new FactureServiceDetails();

                $libre = $f_service_libre[$key];
                $designation = $f_service_designation[$key];
                $periode = $f_service_periode[$key];
                $duree = $f_service_duree[$key];
                $prix = $f_service_prix[$key];
                $montant = $f_service_montant[$key];
                $remise_type_ligne = $f_service_remise_type_ligne[$key];
                $remise_ligne = $f_service_remise_ligne[$key];

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
                $detail->setTypeRemise($remise_type_ligne);
                $detail->setMontantRemise($remise_ligne);
                $detail->setMontant($montant);
                $detail->setFactureService($factureService);

                $em->persist($detail);
                $em->flush();

            }
        }

        if ($f_is_credit == 1 && !$facture->getCredit()) {
            $this->saveCredit($facture);
        }

        return $this->redirectToRoute('facture_service_show',array('id' => $facture->getId()));

    }

    public function saveCredit($facture)
    {

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

        $client = $facture->getClient();
        $montant_ht = $facture->getMontant();
        $remise_type = $facture->getRemiseType();
        $remise = $facture->getRemisePourcentage();
        $montant_remise = $facture->getRemiseValeur();
        $tva = '0.00';
        $montant_tva = '0.00';
        $montant_total = $facture->getTotal();
        $lettre = $facture->getSomme();
        $date = $facture->getDate();
        $agence = $facture->getAgence();

        $credit = new Credit();

        $credit->setClient($client);

        $credit->setHt($montant_ht);
        $credit->setRemiseType($remise_type);
        $credit->setRemise($remise);
        $credit->setMontantRemise($montant_remise);
        $credit->setTva($tva);
        $credit->setMontantTva($montant_tva);
        $credit->setTotal($montant_total);
        $credit->setLettre($lettre);
        $credit->setDate($date);
        $credit->setAgence($agence);
        $credit->setStatut(1); // statut 1 : en cours

        $em = $this->getDoctrine()->getManager();
        $em->persist($credit);
        $em->flush();

        foreach ($details as $detail) {
            $creditDetails = new CreditDetails();

            $type = 2;
            $service = $detail->getService();
            $periode = $detail->getPeriode();
            $duree = $detail->getDuree();
            $prix = $detail->getPrix();
            $montant = $detail->getMontant();
            $type_remise = $detail->getTypeRemise();
            $montant_remise = $detail->getMontantRemise();

            $creditDetails->setType($type);
            $creditDetails->setService($service);
            $creditDetails->setDuree($duree);
            $creditDetails->setPeriode($periode);
            $creditDetails->setPrix($prix);
            $creditDetails->setTypeRemise($type_remise);
            $creditDetails->setMontantRemise($montant_remise);
            $creditDetails->setMontant($montant);
            $creditDetails->setCredit($credit);

            $em->persist($creditDetails);
            $em->flush();

        }

        $facture->setCredit($credit);

        $em->persist($facture);
        $em->flush();

        return new JsonResponse(array(
            'id' => $credit->getId()
        ));
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

        $pdfs = $this->getDoctrine()
                    ->getRepository('AppBundle:PdfAgence')
                    ->findBy(array(
                        'agence' => $agence,
                        'objet' => 1,
                    ));
        if (!empty($pdfs)) {
            $print = true;
        }

        $devises = $this->getDoctrine()
                    ->getRepository('AppBundle:Devise')
                    ->findBy(array(
                        'agence' => $agence
                    ));

        $deviseEntrepot = $this->getDevise();

        $checkFactureBonCommande = $this->checkFactureBonCommande();


        return $this->render('FactureBundle:FactureService:show.html.twig', array(
            'agence' => $agence,
            'deviseEntrepot' => $deviseEntrepot,
            'devises' => $devises,
            'facture' => $facture,
            'factureService' => $factureService,
            'details' => $details,
            'services' => $services,
            'clients' => $clients,
            'permissions' => $permissions,
            'print' => $print,
            'definitif' => $definitif,
            'checkFactureBonCommande' => $checkFactureBonCommande,
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

        $modelePdf = $facture->getModelePdf(); 

        $deviseEntrepot = $this->getDevise();

        $template = $this->renderView('FactureBundle:FactureService:pdf.html.twig', array(
            'agence' => $agence,
            'deviseEntrepot' => $deviseEntrepot,
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

    public function bonCommandeAction($id)
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

        $client = $facture->getClient();
        $montant_ht = $facture->getMontant();
        $remise_type = $facture->getRemiseType();
        $remise = $facture->getRemisePourcentage();
        $montant_remise = $facture->getRemiseValeur();
        $tva = '0.00';
        $montant_tva = '0.00';
        $montant_total = $facture->getTotal();
        $lettre = $facture->getSomme();
        $date = $facture->getDate();
        $agence = $facture->getAgence();

        $bonCommande = new BonCommande();

        $bonCommande->setClient($client);

        $bonCommande->setHt($montant_ht);
        $bonCommande->setRemiseType($remise_type);
        $bonCommande->setRemise($remise);
        $bonCommande->setMontantRemise($montant_remise);
        $bonCommande->setTva($tva);
        $bonCommande->setMontantTva($montant_tva);
        $bonCommande->setTotal($montant_total);
        $bonCommande->setLettre($lettre);
        $bonCommande->setDate($date);
        $bonCommande->setAgence($agence);
        $bonCommande->setStatut(1); // statut 1 : en cours

        $em = $this->getDoctrine()->getManager();
        $em->persist($bonCommande);
        $em->flush();

        foreach ($details as $detail) {
            $panier = new PannierBon();

            $type = 2;
            $libre = $detail->getLibre();
            $service = $detail->getService();
            $periode = $detail->getPeriode();
            $duree = $detail->getDuree();
            $prix = $detail->getPrix();
            $montant = $detail->getMontant();
            $type_remise = $detail->getTypeRemise();
            $montant_remise = $detail->getMontantRemise();

            if ($libre) {
                $designation = $detail->getDesignation();

                $panier->setDesignationAutre($designation);
                $panier->setType(3);
            } else {
                $panier->setType($type);
            }

            $panier->setService($service);
            $panier->setDuree($duree);
            $panier->setPeriode($periode);
            $panier->setPrix($prix);
            $panier->setTypeRemise($type_remise);
            $panier->setMontantRemise($montant_remise);
            $panier->setMontant($montant);
            $panier->setBonCommande($bonCommande);

            $em->persist($panier);
            $em->flush();

        }

        $factureService->setBonCommande($bonCommande);

        $em->persist($factureService);
        $em->flush();

        return new JsonResponse(array(
            'id' => $bonCommande->getId()
        ));


    }

    public function creerDefinitifAction($id)
    {
        $facture  = $this->getDoctrine()
                        ->getRepository('AppBundle:Facture')
                        ->find($id);

        $this->duplicateToDefinitif($facture);

        return new JsonResponse(array(
            'id' => $facture->getNum()
        ));

        
    }

    public function duplicateToDefinitif($facture)
    {

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

        $factureDefinitif = new Facture();
        $factureServiceDefinitif = new FactureService();
        $num = $facture->getNum();
        $f_type = 2;
        $f_model = $facture->getModele();
        $montant = $facture->getMontant();
        $f_remise_type = $facture->getRemiseType();
        $f_remise = $facture->getRemisePourcentage();
        $remise = $facture->getRemiseValeur();
        $total = $facture->getTotal();
        $somme = $facture->getSomme();
        $descr = $facture->getDescr();
        $client = $facture->getClient();
        $date = $facture->getDate();
        $agence = $facture->getAgence();
        $f_is_credit = $facture->getIsCredit();
        $dateCreation = new \DateTime('now');

        $factureDefinitif->setNum($num);
        $factureDefinitif->setType($f_type);
        $factureDefinitif->setModele($f_model);
        $factureDefinitif->setMontant($montant);
        $factureDefinitif->setRemiseType($f_remise_type);
        $factureDefinitif->setRemisePourcentage($f_remise);
        $factureDefinitif->setRemiseValeur($remise);
        $factureDefinitif->setTotal($total);
        $factureDefinitif->setSomme($somme);
        $factureDefinitif->setDescr($descr);
        $factureDefinitif->setClient($client);
        $factureDefinitif->setIsCredit($f_is_credit);
        $factureDefinitif->setDateCreation($dateCreation);
        $factureDefinitif->setDate($date);
        $factureDefinitif->setAgence($agence);
        $factureDefinitif->setProforma($facture);

        $em = $this->getDoctrine()->getManager();
        $em->persist($factureDefinitif);
        $em->flush();

        $factureServiceDefinitif->setFacture($factureDefinitif);

        $em->persist($factureServiceDefinitif);
        $em->flush();

        foreach ($details as $detail) {
            $detailDefinitif = new FactureServiceDetails();

            $libre = $detail->getLibre();
            $periode = $detail->getPeriode();
            $duree = $detail->getDuree();
            $prix = $detail->getPrix();
            $montant = $detail->getMontant();
            $remise_type_ligne = $detail->getTypeRemise();
            $remise_ligne = $detail->getMontantRemise();

            if ($libre == 1) {
                $designation = $detail->getDesignation();
                $detailDefinitif->setDesignation($designation);
            } else {
                $service = $detail->getService();
                $detailDefinitif->setService($service);
            }

            $detailDefinitif->setLibre($libre);
            $detailDefinitif->setPeriode($periode ? $periode : '0.00');
            $detailDefinitif->setDuree($duree);
            $detailDefinitif->setPrix($prix);
            $detailDefinitif->setTypeRemise($remise_type_ligne);
            $detailDefinitif->setMontantRemise($remise_ligne);
            $detailDefinitif->setMontant($montant);
            $detailDefinitif->setFactureService($factureServiceDefinitif);

            $em->persist($detailDefinitif);
            $em->flush();
            
        }

        return $factureDefinitif->getId();

    }

    public function prixAction(Request $request)
    {
        $id = $request->request->get('id');
        $duree = $request->request->get('duree');

        $service  = $this->getDoctrine()
                        ->getRepository('AppBundle:Service')
                        ->find($id);

        $prix = 0;

        if ($service) {


            if (!$duree) {
                $tarif  = $this->getDoctrine()
                            ->getRepository('AppBundle:TarifService')
                            ->findOneBy(array(
                                'service' => $service,
                                'type' => 2,
                            ));

                if ($tarif) {
                    $prix = $tarif->getPrix();
                }
            } else {
                $tarif  = $this->getDoctrine()
                            ->getRepository('AppBundle:TarifService')
                            ->findOneBy(array(
                                'service' => $service,
                                'duree' => $duree,
                            ));

                if ($tarif) {
                    $prix = $tarif->getPrix();
                }
            }

        }

        return new JsonResponse(array(
            'prix' => $prix
        ));

    }

    public function getDevise()
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $agence = $userAgence->getAgence();

        $userEntrepot = $this->getDoctrine()
                    ->getRepository('AppBundle:UserEntrepot')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $devise = array(
            'symbole' => $agence->getDeviseSymbole(),
            'lettre' => $agence->getDeviseLettre(),
        );

        if ($userEntrepot) {

            $entrepot = $userEntrepot->getEntrepot();


            if ($entrepot) {
                if ($entrepot->getDeviseSymbole()) {
                    $devise = array(
                        'symbole' => $entrepot->getDeviseSymbole(),
                        'lettre' => $entrepot->getDeviseLettre(),
                    );
                }
            }
        }


        return $devise;
        

    }

}
