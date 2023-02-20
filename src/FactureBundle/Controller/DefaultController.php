<?php

namespace FactureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Facture;
use AppBundle\Entity\FactureDetails;
use Symfony\Component\HttpFoundation\JsonResponse;
use FactureBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;


class DefaultController extends BaseController
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

        $agence = $userAgence->getAgence();

    	$clients = $this->getDoctrine()
            ->getRepository('AppBundle:Client')
            ->findBy(array(
                'agence' => $agence
            ));

        $variations = $this->getDoctrine()
                ->getRepository('AppBundle:VariationProduit')
                ->list($agence->getId());

        $services = $this->getDoctrine()
            ->getRepository('AppBundle:Service')
            ->findBy(array(
                'agence' => $agence
            ));

        $commandes = $this->getDoctrine()
                        ->getRepository('AppBundle:Commande')
                        ->consultation($agence->getId());

        $chambres = $this->getDoctrine()
                    ->getRepository('AppBundle:Chambre')
                    ->findBy(array(
                        'agence' => $agence
                    ));

        $plats = $this->getDoctrine()
                ->getRepository('AppBundle:Plat')
                ->findBy(array(
                    'agence' => $agence,
                    'statut' => 1,
                ));

        $accompagnements = $this->getDoctrine()
                ->getRepository('AppBundle:Accompagnement')
                ->findBy(array(
                    'agence' => $agence,
                ));

        $bookings = $this->getDoctrine()
                ->getRepository('AppBundle:Booking')
                ->getList(
                    $agence->getId(),
                    '',
                    '',
                    0
                );

        $devises = $this->getDoctrine()
                    ->getRepository('AppBundle:Devise')
                    ->findBy(array(
                        'agence' => $agence
                    ));

        $deviseEntrepot = $this->getDevise();

        $checkFactureProduit = $this->checkFactureProduit();
        $checkFactureService = $this->checkFactureService();
        $checkFactureCaisse = $this->checkFactureCaisse();
        $checkFactureHebergement = $this->checkFactureHebergement();
        $checkFactureRestaurant = $this->checkFactureRestaurant();

        return $this->render('FactureBundle:Default:add.html.twig',array(
        	'deviseEntrepot' => $deviseEntrepot,
            'agence' => $agence,
            'devises' => $devises,
            'clients' => $clients,
            'variations' => $variations,
            'services' => $services,
            'permissions' => $permissions,
            'userAgence' => $userAgence,
            'commandes' => $commandes,
            'chambres' => $chambres,
            'plats' => $plats,
            'accompagnements' => $accompagnements,
            'bookings' => $bookings,
            'checkFactureProduit' => $checkFactureProduit,
            'checkFactureService' => $checkFactureService,
            'checkFactureCaisse' => $checkFactureCaisse,
            'checkFactureHebergement' => $checkFactureHebergement,
            'checkFactureRestaurant' => $checkFactureRestaurant,
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

        if ($f_id) 
        {
            $facture = $this->getDoctrine()
                ->getRepository('AppBundle:Facture')
                ->find($f_id);
             $newNum = str_pad($facture->getNum(), 3, '0', STR_PAD_LEFT);
        }
        else
        {
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
        $f_qte = $request->request->get('f_qte');
        $f_montant = $request->request->get('f_montant');

        if (!empty($f_designation)) {
            foreach ($f_designation as $key => $value) {
                $detail = new FactureDetails();
                $designation = $f_designation[$key];
                $prix = $f_prix[$key];
                $qte = $f_qte[$key];
                $montant = $f_montant[$key];

                $detail->setDesignation($designation);
                $detail->setPrix($prix);
                $detail->setQte($qte);
                $detail->setMontant($montant);
                $detail->setFacture($facture);

                $em->persist($detail);
                $em->flush();

            }
        }

        return $this->redirectToRoute('facture_show',array('id' => $facture->getId()));

    }

    public function exportAction(Request $request)
    {
        $user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        $datas = json_decode(urldecode($request->request->get('datas')));
        // $type_date = $request->request->get('type_date');
        // $date_specifique = $request->request->get('date_specifique');

        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        // $backgroundTitle = '808080';
        $phpExcelObject->getProperties()->setCreator("SHISSAB")
            ->setLastModifiedBy("SHISSAB")
            ->setTitle("Export excel Factures")
            ->setSubject("Export excel Factures")
            ->setDescription("Export excel Factures")
            ->setKeywords("factures")
            ->setCategory("export excel");
        $sheet = $phpExcelObject->setActiveSheetIndex(0);


        $sheet->setCellValue('A1', $agence->getNom());
        $sheet->setCellValue('A2', 'Liste des Factures');

        /*Titre*/
        $sheet->setCellValue('A4', 'N°Facture');
        $sheet->setCellValue('B4', 'Modèle');
        $sheet->setCellValue('C4', 'Type');
        $sheet->setCellValue('D4', 'Date de création');
        $sheet->setCellValue('E4', 'Date Facture');
        $sheet->setCellValue('F4', 'Client');
        $sheet->setCellValue('G4', 'Total');
        // $sheet->setCellValue('H4', 'Montant');

        $sheet->getStyle('A4:G4')
            ->getFill()
            ->setFillType('solid')
            ->getStartColor()->setRGB('c0c0c0');


        foreach(range('A','G') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        

        $index = 5;

        $totalGeneral = 0; 
        // $totalStock = 0 ;

        foreach ($datas as $data) {

            $v = $data->modele ;
            $dataModele = "AUCUNE" ;
            if ($v == 1) {
                $dataModele = 'PRODUIT';
            }
            else if ($v == 2) {
                $dataModele = 'PRESTATION';
            }
            else if ($v == 3) {
                $dataModele = 'PRODUIT & PRESTATION';
            }
            else if ($v == 4) {
                $dataModele = 'HÉBERGEMENT';
            }

           $sheet->setCellValue('A'.$index,$data->num_fact); 
           $sheet->setCellValue('B'.$index,$dataModele); 
           $sheet->setCellValue('C'.$index,$data->type); 
           $sheet->setCellValue('D'.$index,$data->date_creation); 
           $sheet->setCellValue('E'.$index,$data->date); 
           $sheet->setCellValue('F'.$index,$data->client); 
           $sheet->setCellValue('G'.$index,round($data->total * 100)/100); 
            $totalGeneral += $data->total;
            $index++;
        }

        $tindex = $index + 1;

        $sheet->setCellValue('A'.$tindex,'Total'); 
        // $sheet->setCellValue('E'.$tindex, $totalStock); 
        $sheet->setCellValue('G'.$tindex,$totalGeneral); 

        // $sheet->getStyle('H'.$tindex)
        //     ->getFill()
        //     ->setFillType('solid')
        //     ->getStartColor()->setRGB('b8e4bb');

        $phpExcelObject->getActiveSheet()->setTitle('FACTURES');
        $phpExcelObject->setActiveSheetIndex(0);

        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);

        $ext = '.xls';

        $name = 'factures';

        $name = str_replace('/','-',$name);

        $name .= $ext;

        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $name
        );

        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
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
        $definitif = $this->getDoctrine()
                        ->getRepository('AppBundle:Facture')
                        ->findOneBy(array(
                            'proforma' => $facture
                        ));

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

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

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

        $checkFactureBonCommande = $this->checkFactureBonCommande();


        return $this->render('FactureBundle:Default:show.html.twig', array(
            'facture' => $facture,
            'clients' => $clients,
            'details' => $details,
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

        $modelePdf = $facture->getModelePdf();      

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

        $checkFactureProduit = $this->checkFactureProduit();
        $checkFactureService = $this->checkFactureService();
        $checkFactureCaisse = $this->checkFactureCaisse();
        $checkFactureHebergement = $this->checkFactureHebergement();
        $checkFactureRestaurant = $this->checkFactureRestaurant();

        return $this->render('FactureBundle:Default:consultation.html.twig',array(
            'agence' => $agence,
            'checkFactureProduit' => $checkFactureProduit,
            'checkFactureService' => $checkFactureService,
            'checkFactureCaisse' => $checkFactureCaisse,
            'checkFactureHebergement' => $checkFactureHebergement,
            'checkFactureRestaurant' => $checkFactureRestaurant,
        ));
    }

    public function consultationCorbeilleAction()
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $checkFactureProduit = $this->checkFactureProduit();
        $checkFactureService = $this->checkFactureService();
        $checkFactureCaisse = $this->checkFactureCaisse();
        $checkFactureHebergement = $this->checkFactureHebergement();
        $checkFactureRestaurant = $this->checkFactureRestaurant();

        return $this->render('FactureBundle:Default:consultation_corbeille.html.twig',array(
            'agence' => $agence,
            'checkFactureProduit' => $checkFactureProduit,
            'checkFactureService' => $checkFactureService,
            'checkFactureCaisse' => $checkFactureCaisse,
            'checkFactureHebergement' => $checkFactureHebergement,
            'checkFactureRestaurant' => $checkFactureRestaurant,
        ));
    }

    public function listAction(Request $request)
    { 
        $filtre_modele = $request->request->get('filtre_modele');
        $filtre_type = $request->request->get('filtre_type');
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
                $par_agence,
                $filtre_modele,
                $filtre_type
            );

        // usort($factures, function($a,$b){
        //         return \DateTime::createFromFormat('j/m/Y', $a['date_creation']) <= \DateTime::createFromFormat('j/m/Y', $b['date_creation']);
        // });

        array_multisort(
            array_map('strtotime',array_column($factures,'dc')),SORT_DESC, 
            array_column($factures, 'num'), SORT_DESC,
            array_column($factures, 'id'), SORT_DESC,
            $factures
        );

        return new JsonResponse($factures);
    }

    public function listCorbeilleAction(Request $request)
    {
        $filtre_modele = $request->request->get('filtre_modele');
        $filtre_type = $request->request->get('filtre_type');
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
            ->consultationCorbeille(
                $recherche_par,
                $a_rechercher,
                $type_date,
                $mois,
                $annee,
                $date_specifique,
                $debut_date,
                $fin_date,
                $par_agence,
                $filtre_modele,
                $filtre_type
            );

        // usort($factures, function($a,$b){
        //         return \DateTime::createFromFormat('j/m/Y', $a['date_creation']) <= \DateTime::createFromFormat('j/m/Y', $b['date_creation']);
        // });

        array_multisort(
            array_map('strtotime',array_column($factures,'dc')),SORT_DESC, 
            array_column($factures, 'num'), SORT_DESC,
            array_column($factures, 'id'), SORT_DESC,
            $factures
        );

        return new JsonResponse($factures);
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
        $details = $this->getDoctrine()
                    ->getRepository('AppBundle:FactureDetails')
                    ->findBy(array(
                        'facture' => $facture
                    ));

        $factureDefinitif = new Facture();
        $num = $facture->getNum();
        $f_type = 2;
        $montant = $facture->getMontant();
        $f_remise = $facture->getRemisePourcentage();
        $remise = $facture->getRemiseValeur();
        $total = $facture->getTotal();
        $somme = $facture->getSomme();
        $descr = $facture->getDescr();
        $client = $facture->getClient();
        $date = $facture->getDate();
        $agence = $facture->getAgence();
        $dateCreation = new \DateTime('now');

        $factureDefinitif->setNum($num);
        $factureDefinitif->setType($f_type);
        $factureDefinitif->setMontant($montant);
        $factureDefinitif->setRemisePourcentage($f_remise);
        $factureDefinitif->setRemiseValeur($remise);
        $factureDefinitif->setTotal($total);
        $factureDefinitif->setSomme($somme);
        $factureDefinitif->setDescr($descr);
        $factureDefinitif->setClient($client);
        $factureDefinitif->setDateCreation($dateCreation);
        $factureDefinitif->setDate($date);
        $factureDefinitif->setAgence($agence);
        $factureDefinitif->setProforma($facture);

        $em = $this->getDoctrine()->getManager();
        $em->persist($factureDefinitif);
        $em->flush();

        foreach ($details as $detail) {
            $detailDefinitif = new FactureDetails();

            $designation = $detail->getDesignation();
            $prix = $detail->getPrix();
            $qte = $detail->getQte();
            $montant = $detail->getMontant();

            $detailDefinitif->setDesignation($designation);
            $detailDefinitif->setPrix($prix);
            $detailDefinitif->setQte($qte);
            $detailDefinitif->setMontant($montant);
            $detailDefinitif->setFacture($factureDefinitif);

            $em->persist($detailDefinitif);
            $em->flush();
            
        }

        return $factureDefinitif->getId();

    }

    public function deleteAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $facture  = $this->getDoctrine()
                        ->getRepository('AppBundle:Facture')
                        ->find($id);

        $credit = $facture->getCredit();

        $facture_details  = ($facture) ? $this->getDoctrine()
                        ->getRepository('AppBundle:FactureDetails')
                        ->findBy(array(
                            'facture' => $facture
                        )) : null ;

        // Facture de produit 
        $facture_produit = $this->getDoctrine()
                            ->getRepository('AppBundle:FactureProduit')
                            ->findOneBy(array(
                                'facture' => $facture
                            ));
        $facture_produit_details = ($facture_produit) ? $this->getDoctrine()
                                ->getRepository('AppBundle:FactureProduitDetails')
                                ->findBy(array(
                                    'factureProduit' => $facture_produit
                                )) : null ;
        $bon_commande_fp  = ($facture_produit) ? $facture_produit->getBonCommande() : null;
        $bon_commande_details_fp = ($bon_commande_fp) ? $this->getDoctrine()
                                ->getRepository('AppBundle:PannierBon')
                                ->findBy(array(
                                    'bonCommande' => $bon_commande_fp
                                )) : null ;
        $bon_livraison_fp = ($bon_commande_fp) ? $this->getDoctrine()
                                ->getRepository('AppBundle:BonLivraison')
                                ->findBy(array(
                                    'bonCommande' => $bon_commande_fp
                                )) : null ;
        $bon_livraison_details_fp = ($bon_livraison_fp) ? $this->getDoctrine()
                                ->getRepository('AppBundle:BonLivraisonDetails')
                                ->findBy(array(
                                    'bonLivraison' => $bon_livraison_fp
                                )) : null ;
        if($facture_produit){
            if($facture_produit_details){
                for ($i=0; $i < count($facture_produit_details) ; $i++) {
                    $em->remove($facture_produit_details[$i]);
                    $em->flush();
                }
            }
            if ($bon_commande_fp){
                if($bon_commande_details_fp){
                    for ($i=0; $i < count($bon_commande_details_fp) ; $i++) { 
                    $em->remove($bon_commande_details_fp[$i]);
                    $em->flush();    
                    }    
                }
                if($bon_livraison_fp){
                    if($bon_livraison_details_fp){
                        for ($i=0; $i < count($bon_livraison_details_fp) ; $i++) {
                            $em->remove($bon_livraison_details_fp[$i]);
                            $em->flush();
                        }
                    } 
                    for ($compteur=0; $compteur < count($bon_livraison_fp) ; $compteur++) {
                        $em->remove($bon_livraison_fp[$compteur]);
                        $em->flush();
                    }
                }
                $em->remove($facture_produit);
                $em->flush();
                $em->remove($bon_commande_fp);
                $em->flush();
            }else{
                $em->remove($facture_produit);
                $em->flush();
            }
        }

        // Facture d'hebergement
        $facture_hebergement = $this->getDoctrine()
                                ->getRepository('AppBundle:FactureHebergement')
                                ->findOneBy(array(
                                    'facture' => $facture
                                ));
        $facture_hebergement_details = ($facture_hebergement) ? $this->getDoctrine()
                                ->getRepository('AppBundle:FactureHebergementDetails')
                                ->findBy(array(
                                    'factureHebergement' => $facture_hebergement
                                )) : null ;
        if($facture_hebergement){
            for ($i=0; $i < count($facture_hebergement_details) ; $i++) {
                $em->remove($facture_hebergement_details[$i]);
                $em->flush();
            }
            $em->remove($facture_hebergement);
            $em->flush();
        }

        // Facture de produit service
        $facture_produit_service = $this->getDoctrine()
                                    ->getRepository('AppBundle:FactureProduitService')
                                    ->findOneBy(array(
                                        'facture' => $facture
                                    ));
        $facture_produit_service_details = ($facture_produit_service) ? $this->getDoctrine()
                                ->getRepository('AppBundle:FactureProduitServiceDetails')
                                ->findBy(array(
                                    'factureProduitService' => $facture_produit_service
                                )) : null ;
        $bon_commande_fps  = ($facture_produit_service) ? $facture_produit_service->getBonCommande() : null;
        $bon_commande_details_fps = ($bon_commande_fp) ? $this->getDoctrine()
                                ->getRepository('AppBundle:PannierBon')
                                ->findBy(array(
                                    'bonCommande' => $bon_commande_fps
                                )) : null ;
        $bon_livraison_fps = ($bon_commande_fps) ? $this->getDoctrine()
                                ->getRepository('AppBundle:BonLivraison')
                                ->findBy(array(
                                    'bonCommande' => $bon_commande_fps
                                )) : null ;
        $bon_livraison_details_fps = ($bon_livraison_fps) ? $this->getDoctrine()
                                ->getRepository('AppBundle:BonLivraisonDetails')
                                ->findBy(array(
                                    'bonLivraison' => $bon_livraison_fps
                                )) : null ;
        if($facture_produit_service){
            if($facture_produit_service_details){
                for ($i=0; $i < count($facture_produit_service_details) ; $i++) {
                    $em->remove($facture_produit_service_details[$i]);
                    $em->flush();
                }
            }
            if ($bon_commande_fps){
                if($bon_commande_details_fps){
                    for ($i=0; $i < count($bon_commande_details_fps) ; $i++) { 
                        $em->remove($bon_commande_details_fps[$i]);
                        $em->flush();
                    }    
                }
                if($bon_livraison_fps){
                    if($bon_livraison_details_fps){
                        for ($i=0; $i < count($bon_livraison_details_fps) ; $i++) {
                            $em->remove($bon_livraison_details_fps[$i]);
                            $em->flush();
                        }
                    }
                    for ($compteur=0; $compteur < count($bon_livraison_fps) ; $compteur++) {
                        $em->remove($bon_livraison_fps[$compteur]);
                        $em->flush();
                    }
                }
                $em->remove($facture_produit_service);
                $em->flush();
                $em->remove($bon_commande_fps);
                $em->flush();
            }else{
                $em->remove($facture_produit_service);
                $em->flush();
            }
        }

        // Facture de service
        $facture_service = $this->getDoctrine()
                                    ->getRepository('AppBundle:FactureService')
                                    ->findOneBy(array(
                                        'facture' => $facture
                                    ));
        $facture_service_details = ($facture_service) ? $this->getDoctrine()
                                ->getRepository('AppBundle:FactureServiceDetails')
                                ->findBy(array(
                                    'factureService' => $facture_service
                                )) : null ;
        $bon_commande_s = ($facture_service) ? $facture_service->getBonCommande() : null;
        $bon_commande_details_s = ($bon_commande_s) ? $this->getDoctrine()
                                ->getRepository('AppBundle:PannierBon')
                                ->findBy(array(
                                    'bonCommande' => $bon_commande_s
                                )) : null ;
        $bon_livraison_s = ($bon_commande_s) ? $this->getDoctrine()
                                ->getRepository('AppBundle:BonLivraison')
                                ->findBy(array(
                                    'bonCommande' => $bon_commande_s
                                )) : null ;
        $bon_livraison_details_s = ($bon_livraison_s) ? $this->getDoctrine()
                                ->getRepository('AppBundle:BonLivraisonDetails')
                                ->findBy(array(
                                    'bonLivraison' => $bon_livraison_s
                                )) : null ;
        if($facture_service){
            if($facture_service_details){
                for ($i=0; $i < count($facture_service_details) ; $i++) {
                    $em->remove($facture_service_details[$i]);
                    $em->flush();
                }
            }
            if ($bon_commande_s){
                if($bon_commande_details_s){
                    for ($i=0; $i < count($bon_commande_details_s) ; $i++) { 
                        $em->remove($bon_commande_details_s[$i]);
                        $em->flush();    
                    }
                }
                if($bon_livraison_s){
                    if($bon_livraison_details_s){
                        for ($i=0; $i < count($bon_livraison_details_s) ; $i++) {
                            $em->remove($bon_livraison_details_s[$i]);
                            $em->flush();
                        }
                    }
                    for ($compteur=0; $compteur < count($bon_livraison_s) ; $compteur++) {
                        $em->remove($bon_livraison_s[$compteur]);
                        $em->flush();
                    }
                }
                $em->remove($facture_service);
                $em->flush();
                $em->remove($bon_commande_s);
                $em->flush();
            }else{
                $em->remove($facture_service);
                $em->flush();
            }
        }

        // Facture de resto
        $facture_restaurant = $this->getDoctrine()
                                    ->getRepository('AppBundle:FactureRestaurant')
                                    ->findOneBy(array(
                                        'facture' => $facture
                                    ));
        $facture_restaurant_details = ($facture_restaurant) ? $this->getDoctrine()
                                ->getRepository('AppBundle:FactureRestaurantDetails')
                                ->findBy(array(
                                    'factureRestaurant' => $facture_restaurant
                                )) : null ;
        if($facture_restaurant){
            for ($i=0; $i < count($facture_restaurant_details) ; $i++) {
                $em->remove($facture_restaurant_details[$i]);
                $em->flush();
            }
            $em->remove($facture_restaurant);
            $em->flush();
        }

        if($facture){
            if($facture_details){
                for ($i=0; $i < count($facture_details) ; $i++) {
                    $em->remove($facture_details[$i]);
                    $em->flush();
                }
            }
            $em->remove($facture);
            $em->flush();
        }

        // Credit
        if($credit) {
            $_credit_details = $this->getDoctrine()
                        ->getRepository('AppBundle:CreditDetails')
                        ->findBy(array(
                            'credit' => $credit
                        ));
            for ($i=0; $i < count($_credit_details) ; $i++) {
                $em->remove($_credit_details[$i]);
                $em->flush();
            }

            $em->remove($credit);
            $em->flush();
        }

    //     $em = $this->getDoctrine()->getManager();
    //     $em->remove($facture);
    //     $em->flush();

        return new JsonResponse(200);
    }

    public function archiveAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $facture  = $this->getDoctrine()
                        ->getRepository('AppBundle:Facture')
                        ->find($id);

        // Facture de produit 
        $facture_produit = $this->getDoctrine()
                            ->getRepository('AppBundle:FactureProduit')
                            ->findOneBy(array(
                                'facture' => $facture
                            ));

        $bon_commande_fp  = ($facture_produit) ? $facture_produit->getBonCommande() : null;

        $bon_livraison_fp = ($bon_commande_fp) ? $this->getDoctrine()
                                ->getRepository('AppBundle:BonLivraison')
                                ->findBy(array(
                                    'bonCommande' => $bon_commande_fp
                                )) : null ;

        if($facture_produit){
            if ($bon_commande_fp){
                if($bon_livraison_fp){
                    for ($compteur=0; $compteur < count($bon_livraison_fp) ; $compteur++) {
                        $bon_livraison_fp[$compteur]->setIsDelete(1);
                        $em->flush();
                    }
                }
                $bon_commande_fp->setIsDelete(1);
                $em->flush();
            }
            $facture_produit->setIsDelete(1);
            $em->flush();
        }

        // Facture d'hebergement
        $facture_hebergement = $this->getDoctrine()
                                ->getRepository('AppBundle:FactureHebergement')
                                ->findOneBy(array(
                                    'facture' => $facture
                                ));

        if($facture_hebergement){
            $facture_hebergement->setIsDelete(1);
            $em->flush();
        }

        // Facture de produit service
        $facture_produit_service = $this->getDoctrine()
                                    ->getRepository('AppBundle:FactureProduitService')
                                    ->findOneBy(array(
                                        'facture' => $facture
                                    ));

        $bon_commande_fps  = ($facture_produit_service) ? $facture_produit_service->getBonCommande() : null;

        $bon_livraison_fps = ($bon_commande_fps) ? $this->getDoctrine()
                                ->getRepository('AppBundle:BonLivraison')
                                ->findBy(array(
                                    'bonCommande' => $bon_commande_fps
                                )) : null ;

        if($facture_produit_service){
            if ($bon_commande_fps){
                if($bon_livraison_fps){
                    for ($compteur=0; $compteur < count($bon_livraison_fps) ; $compteur++) {
                        $bon_livraison_fps[$compteur]->setIsDelete(1);
                        $em->flush();
                    }
                }
                $bon_commande_fps->setIsDelete(1);
                $em->flush();
            }
            $facture_produit_service->setIsDelete(1);
            $em->flush();
        }

        // Facture de service
        $facture_service = $this->getDoctrine()
                                    ->getRepository('AppBundle:FactureService')
                                    ->findOneBy(array(
                                        'facture' => $facture
                                    ));
        $bon_commande_s  = ($facture_service) ? $facture_service->getBonCommande() : null;
        $bon_livraison_s = ($bon_commande_s) ? $this->getDoctrine()
                                ->getRepository('AppBundle:BonLivraison')
                                ->findBy(array(
                                    'bonCommande' => $bon_commande_s
                                )) : null ;

        if($facture_service){
            if ($bon_commande_s){
                if($bon_livraison_s){
                    for ($compteur=0; $compteur < count($bon_livraison_s) ; $compteur++) {
                        $bon_livraison_s[$compteur]->setIsDelete(1);
                        $em->flush();
                    }
                }
                $bon_commande_s->setIsDelete(1);
                $em->flush();
            }
            $facture_service->setIsDelete(1);
            $em->flush();
        }

        // Facture de resto
        $facture_restaurant = $this->getDoctrine()
                                    ->getRepository('AppBundle:FactureRestaurant')
                                    ->findOneBy(array(
                                        'facture' => $facture
                                    ));
        if($facture_restaurant){
            $facture_restaurant->setIsDelete(1);
            $em->flush();
        }

        if($facture){
            $facture->setIsDelete(1);
            $em->flush();
        }


        return new JsonResponse(200);
    }

    public function rearchiveAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $facture  = $this->getDoctrine()
                        ->getRepository('AppBundle:Facture')
                        ->find($id);

        // Facture de produit 
        $facture_produit = $this->getDoctrine()
                            ->getRepository('AppBundle:FactureProduit')
                            ->findOneBy(array(
                                'facture' => $facture
                            ));

        $bon_commande_fp  = ($facture_produit) ? $facture_produit->getBonCommande() : null;

        $bon_livraison_fp = ($bon_commande_fp) ? $this->getDoctrine()
                                ->getRepository('AppBundle:BonLivraison')
                                ->findBy(array(
                                    'bonCommande' => $bon_commande_fp
                                )) : null ;

        if($facture_produit){
            if ($bon_commande_fp){
                if($bon_livraison_fp){
                    for ($compteur=0; $compteur < count($bon_livraison_fp) ; $compteur++) {
                        $bon_livraison_fp[$compteur]->setIsDelete(null);
                        $em->flush();
                    }
                }
                $bon_commande_fp->setIsDelete(null);
                $em->flush();
            }
            $facture_produit->setIsDelete(null);
            $em->flush();
        }

        // Facture d'hebergement
        $facture_hebergement = $this->getDoctrine()
                                ->getRepository('AppBundle:FactureHebergement')
                                ->findOneBy(array(
                                    'facture' => $facture
                                ));

        if($facture_hebergement){
            $facture_hebergement->setIsDelete(null);
            $em->flush();
        }

        // Facture de produit service
        $facture_produit_service = $this->getDoctrine()
                                    ->getRepository('AppBundle:FactureProduitService')
                                    ->findOneBy(array(
                                        'facture' => $facture
                                    ));

        $bon_commande_fps  = ($facture_produit_service) ? $facture_produit_service->getBonCommande() : null;

        $bon_livraison_fps = ($bon_commande_fps) ? $this->getDoctrine()
                                ->getRepository('AppBundle:BonLivraison')
                                ->findBy(array(
                                    'bonCommande' => $bon_commande_fps
                                )) : null ;

        if($facture_produit_service){
            if ($bon_commande_fps){
                if($bon_livraison_fps){
                    for ($compteur=0; $compteur < count($bon_livraison_fps) ; $compteur++) {
                        $bon_livraison_fps[$compteur]->setIsDelete(null);
                        $em->flush();
                    }
                }
                $bon_commande_fps->setIsDelete(null);
                $em->flush();
            }
            $facture_produit_service->setIsDelete(null);
            $em->flush();
        }

        // Facture de service
        $facture_service = $this->getDoctrine()
                                    ->getRepository('AppBundle:FactureService')
                                    ->findOneBy(array(
                                        'facture' => $facture
                                    ));
        $bon_commande_s  = ($facture_service) ? $facture_service->getBonCommande() : null;
        $bon_livraison_s = ($bon_commande_s) ? $this->getDoctrine()
                                ->getRepository('AppBundle:BonLivraison')
                                ->findBy(array(
                                    'bonCommande' => $bon_commande_s
                                )) : null ;

        if($facture_service){
            if ($bon_commande_s){
                if($bon_livraison_s){
                    for ($compteur=0; $compteur < count($bon_livraison_s) ; $compteur++) {
                        $bon_livraison_s[$compteur]->setIsDelete(null);
                        $em->flush();
                    }
                }
                $bon_commande_s->setIsDelete(null);
                $em->flush();
            }
            $facture_service->setIsDelete(null);
            $em->flush();
        }

        // Facture de resto
        $facture_restaurant = $this->getDoctrine()
                                    ->getRepository('AppBundle:FactureRestaurant')
                                    ->findOneBy(array(
                                        'facture' => $facture
                                    ));
        if($facture_restaurant){
            $facture_restaurant->setIsDelete(null);
            $em->flush();
        }

        if($facture){
            $facture->setIsDelete(null);
            $em->flush();
        }

        return new JsonResponse(200);
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
