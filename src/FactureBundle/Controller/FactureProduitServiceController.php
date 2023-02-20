<?php

namespace FactureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Facture;
use AppBundle\Entity\FactureProduitService;
use AppBundle\Entity\FactureProduitServiceDetails;
use AppBundle\Entity\BonCommande;
use AppBundle\Entity\PannierBon;
use AppBundle\Entity\Credit;
use AppBundle\Entity\CreditDetails;
use FactureBundle\Controller\BaseController;

class FactureProduitServiceController extends BaseController
{
	public function tplAction($type)
	{

		$user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence()->getId();

        $deviseEntrepot = $this->getDevise();

		if ($type != 2 ) {

            $tpl = $this->renderView('FactureBundle:FactureProduitService:produit.html.twig');
			
			$designations = $this->getDoctrine()
                ->getRepository('AppBundle:VariationProduit')
                ->list($agence);
	        
        } else {
           $tpl = $this->renderView('FactureBundle:FactureProduitService:service.html.twig');

           $designations  = $this->getDoctrine()
	                        ->getRepository('AppBundle:Service')
	                        ->getList($agence);
        }
        
        return new JsonResponse(array(
        	'tpl' => $tpl,
            'devise' => $deviseEntrepot,
        	'designations' => $designations,
        ));

	}

    public function saveAction(Request $request)
    {

        $f_type = $request->request->get('f_type');
        $f_model = $request->request->get('f_model');
        $f_client = $request->request->get('f_client');
        $f_date = $request->request->get('f_date');
        $f_lieu = $request->request->get('f_lieu');
        $descr = $request->request->get('descr');

        $montant = $request->request->get('ps_montant');
        $f_remise = $request->request->get('f_ps_remise');
        $remise = $request->request->get('ps_remise');
        $f_remise_type = $request->request->get('f_ps_remise_type');
        $total = $request->request->get('ps_total');
        $somme = $request->request->get('somme_produitservice');
        $f_is_credit = $request->request->get('f_is_credit');
        $f_ps_devise = $request->request->get('f_ps_devise');
        $f_ps_montant_converti = $request->request->get('f_ps_montant_converti');

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

             $factureProduitService = $this->getDoctrine()
                ->getRepository('AppBundle:FactureProduitService')
                ->findOneBy(array(
                    'facture' => $facture
                ));
        } else{
            $facture = new Facture();
            $newNum = $this->prepareNewNumFacture($agence->getId());
            $facture->setNum(intval($newNum));

            $factureProduitService = new FactureProduitService();

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

        if ($f_ps_devise) {
            $devise = $this->getDoctrine()
                ->getRepository('AppBundle:Devise')
                ->find($f_ps_devise);
            
            $facture->setDevise($devise);
            $facture->setMontantConverti($f_ps_montant_converti);
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

        $factureProduitService->setFacture($facture);

        $em->persist($factureProduitService);
        $em->flush();

        $details_list = explode(",", $list_id);

        // Suppression de tous les details
        foreach ($details_list as $old_id) {

            if ($old_id != "") {
                $old_detail = $this->getDoctrine()
                                    ->getRepository('AppBundle:FactureProduitServiceDetails')
                                    ->find($old_id);

                $em->remove($old_detail);
                $em->flush();
            }

        }

        $f_ps_type_designation = $request->request->get('f_ps_type_designation');
        $f_ps_designation = $request->request->get('f_ps_designation');
        $f_ps_qte = $request->request->get('f_ps_qte');
        $f_ps_periode = $request->request->get('f_ps_periode');
        $f_ps_duree = $request->request->get('f_ps_duree');
        $f_ps_prix = $request->request->get('f_ps_prix');
        $f_ps_montant = $request->request->get('f_ps_montant');
        $f_ps_remise_type_ligne = $request->request->get('f_ps_remise_type_ligne');
        $f_ps_remise_ligne = $request->request->get('f_ps_remise_ligne');

        if (!empty($f_ps_type_designation)) {
            foreach ($f_ps_type_designation as $key => $value) {
                $type_designation = $f_ps_type_designation[$key];
                $designation = $f_ps_designation[$key];
                $prix = $f_ps_prix[$key];
                $montant = $f_ps_montant[$key];
                $remise_type_ligne = $f_ps_remise_type_ligne[$key];
                $remise_ligne = $f_ps_remise_ligne[$key];

                $detail = new FactureProduitServiceDetails();

                if ($type_designation == 1) {
                    $qte = $f_ps_qte[$key];

                    $variationProduit = $this->getDoctrine()
                        ->getRepository('AppBundle:VariationProduit')
                        ->find( $designation );

                    $detail->setVariationProduit($variationProduit);
                    $detail->setQte($qte);
                } else {
                    $periode = $f_ps_periode[$key];
                    $duree = $f_ps_duree[$key];

                    $service = $this->getDoctrine()
                        ->getRepository('AppBundle:Service')
                        ->find( $designation );

                    $detail->setService($service);
                    $detail->setPeriode($periode);
                    $detail->setDuree($duree);

                }
                
                $detail->setPrix($prix);
                $detail->setTypeRemise($remise_type_ligne);
                $detail->setMontantRemise($remise_ligne);
                $detail->setMontant($montant);
                $detail->setType($type_designation);
                $detail->setFactureProduitService($factureProduitService);

                $em->persist($detail);
                $em->flush();

            }
        }

        if ($f_is_credit == 1 && !$facture->getCredit()) {
            $this->saveCredit($facture);
        }

        return $this->redirectToRoute('facture_produitservice_show',array('id' => $facture->getId()));

    }

    public function saveCredit($facture)
    {

        $factureProduitService  = $this->getDoctrine()
                        ->getRepository('AppBundle:FactureProduitService')
                        ->findOneBy(array(
                            'facture' => $facture
                        ));

        $details = $this->getDoctrine()
                    ->getRepository('AppBundle:FactureProduitServiceDetails')
                    ->findBy(array(
                        'factureProduitService' => $factureProduitService
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

            $type = $detail->getType();
            $prix = $detail->getPrix();
            $montant = $detail->getMontant();
            $type_remise = $detail->getTypeRemise();
            $montant_remise = $detail->getMontantRemise();
            
            if ($type == 1) {
                $variation = $detail->getVariationProduit();
                $qte = $detail->getQte();
                
                $creditDetails->setVariationProduit($variation);
                $creditDetails->setQte($qte);
            } else {
                $service = $detail->getService();
                $periode = $detail->getPeriode();
                $duree = $detail->getDuree();

                $creditDetails->setService($service);
                $creditDetails->setDuree($duree);
                $creditDetails->setPeriode($periode);
            }

            $creditDetails->setType($type);
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

        $factureProduitService  = $this->getDoctrine()
                        ->getRepository('AppBundle:FactureProduitService')
                        ->findOneBy(array(
                            'facture' => $facture
                        ));

        $definitif = $this->getDoctrine()
                        ->getRepository('AppBundle:Facture')
                        ->findOneBy(array(
                            'proforma' => $facture
                        ));

        $details = $this->getDoctrine()
                    ->getRepository('AppBundle:FactureProduitServiceDetails')
                    ->findBy(array(
                        'factureProduitService' => $factureProduitService
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

        $produits = $this->getDoctrine()
            ->getRepository('AppBundle:Produit')
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

        $variations = $this->getDoctrine()
                ->getRepository('AppBundle:VariationProduit')
                ->list($agence->getId());

        $services = $this->getDoctrine()
            ->getRepository('AppBundle:Service')
            ->findBy(array(
                'agence' => $agence
            ));

        $devises = $this->getDoctrine()
                    ->getRepository('AppBundle:Devise')
                    ->findBy(array(
                        'agence' => $agence
                    ));

        $deviseEntrepot = $this->getDevise();

        $checkFactureBonCommande = $this->checkFactureBonCommande();

        return $this->render('FactureBundle:FactureProduitService:show.html.twig', array(
            'agence' => $agence,
            'deviseEntrepot' => $deviseEntrepot,
            'devises' => $devises,
            'facture' => $facture,
            'factureProduitService' => $factureProduitService,
            'details' => $details,
            'produits' => $produits,
            'services' => $services,
            'variations' => $variations,
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

        $factureProduitService  = $this->getDoctrine()
                        ->getRepository('AppBundle:FactureProduitService')
                        ->findOneBy(array(
                            'facture' => $facture
                        ));

        $definitif = $this->getDoctrine()
                        ->getRepository('AppBundle:Facture')
                        ->findOneBy(array(
                            'proforma' => $facture
                        ));

        $details = $this->getDoctrine()
                    ->getRepository('AppBundle:FactureProduitServiceDetails')
                    ->findBy(array(
                        'factureProduitService' => $factureProduitService
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

        $produits = $this->getDoctrine()
            ->getRepository('AppBundle:Produit')
            ->findBy(array(
                'agence' => $agence
            ));

        $print = false;

        $pdfAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:PdfAgence')
                    ->findOneBy(array(
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

        
        $modelePdf = $facture->getModelePdf();   

        $deviseEntrepot = $this->getDevise();

        $template = $this->renderView('FactureBundle:FactureProduitService:pdf.html.twig', array(
            'agence' => $agence,
            'facture' => $facture,
            'factureProduitService' => $factureProduitService,
            'details' => $details,
            'produits' => $produits,
            'services' => $services,
            'variations' => $variations,
            'clients' => $clients,
            'permissions' => $permissions,
            'print' => $print,
            'definitif' => $definitif,
            'modelePdf' => $modelePdf,
            'deviseEntrepot' => $deviseEntrepot,
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

        $factureProduitService  = $this->getDoctrine()
                        ->getRepository('AppBundle:FactureProduitService')
                        ->findOneBy(array(
                            'facture' => $facture
                        ));

        $details = $this->getDoctrine()
                    ->getRepository('AppBundle:FactureProduitServiceDetails')
                    ->findBy(array(
                        'factureProduitService' => $factureProduitService
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

            $type = $detail->getType();
            $prix = $detail->getPrix();
            $montant = $detail->getMontant();
            $type_remise = $detail->getTypeRemise();
            $montant_remise = $detail->getMontantRemise();
            
            if ($type == 1) {
                $variation = $detail->getVariationProduit();
                $qte = $detail->getQte();
                
                $panier->setVariationProduit($variation);
                $panier->setQte($qte);
            } else {
                $service = $detail->getService();
                $periode = $detail->getPeriode();
                $duree = $detail->getDuree();

                $panier->setService($service);
                $panier->setDuree($duree);
                $panier->setPeriode($periode);
            }

            $panier->setType($type);
            $panier->setPrix($prix);
            $panier->setTypeRemise($type_remise);
            $panier->setMontantRemise($montant_remise);
            $panier->setMontant($montant);
            $panier->setBonCommande($bonCommande);

            $em->persist($panier);
            $em->flush();

        }

        $factureProduitService->setBonCommande($bonCommande);

        $em->persist($factureProduitService);
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

        $factureProduitService  = $this->getDoctrine()
                        ->getRepository('AppBundle:FactureProduitService')
                        ->findOneBy(array(
                            'facture' => $facture
                        ));

        $details = $this->getDoctrine()
                    ->getRepository('AppBundle:FactureProduitServiceDetails')
                    ->findBy(array(
                        'factureProduitService' => $factureProduitService
                    ));

        $factureDefinitif = new Facture();
        $factureProduitServiceDefinitif = new FactureProduitService();
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

        $factureProduitServiceDefinitif->setFacture($factureDefinitif);

        $em->persist($factureProduitServiceDefinitif);
        $em->flush();

        foreach ($details as $detail) {
            $detailDefinitif = new FactureProduitServiceDetails();

            $type_designation = $detail->getType();
            $prix = $detail->getPrix();
            $montant = $detail->getMontant();
            $remise_type_ligne = $detail->getTypeRemise();
            $remise_ligne = $detail->getMontantRemise();

            if ($type_designation == 1) {
                $qte = $detail->getQte();
                $variationProduit = $detail->getVariationProduit();

                $detailDefinitif->setVariationProduit($variationProduit);
                $detailDefinitif->setQte($qte);
            } else {
                $periode = $detail->getPeriode();
                $duree = $detail->getDuree();
                $service = $detail->getService();

                $detailDefinitif->setPeriode($periode);
                $detailDefinitif->setDuree($duree);
                $detailDefinitif->setService($service);
            }

            $detailDefinitif->setPrix($prix);
            $detailDefinitif->setTypeRemise($remise_type_ligne);
            $detailDefinitif->setMontantRemise($remise_ligne);
            $detailDefinitif->setMontant($montant);
            $detailDefinitif->setType($type_designation);
            $detailDefinitif->setFactureProduitService($factureProduitServiceDefinitif);

            $em->persist($detailDefinitif);
            $em->flush();
            
        }

        return $factureDefinitif->getId();

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
