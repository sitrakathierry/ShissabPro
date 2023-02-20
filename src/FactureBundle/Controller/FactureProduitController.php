<?php

namespace FactureBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Facture;
use AppBundle\Entity\FactureProduit;
use AppBundle\Entity\FactureProduitDetails;
use AppBundle\Entity\BonCommande;
use AppBundle\Entity\PannierBon;
use AppBundle\Entity\Credit;
use AppBundle\Entity\CreditDetails;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FactureBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class FactureProduitController extends BaseController
{
    public function saveAction(Request $request)
    {
        $f_type = $request->request->get('f_type');
        $f_model = $request->request->get('f_model');
        $f_client = $request->request->get('f_client');
        $f_date = $request->request->get('f_date');
        $f_lieu = $request->request->get('f_lieu');
        $descr = $request->request->get('descr');

        $montant = $request->request->get('montant');
        $f_remise = $request->request->get('f_remise');
        $remise = $request->request->get('remise');
        $f_remise_type = $request->request->get('f_remise_type');
        $total = $request->request->get('p_total');
        $somme = $request->request->get('somme');
        $f_recu = $request->request->get('f_recu');
        $f_is_credit = $request->request->get('f_is_credit');
        $f_auto_devise = $request->request->get('f_auto_devise');
        $f_auto_montant_converti = $request->request->get('f_auto_montant_converti');

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

             $factureProduit = $this->getDoctrine()
                ->getRepository('AppBundle:FactureProduit')
                ->findOneBy(array(
                    'facture' => $facture
                ));
        } else{
            $facture = new Facture();
            $newNum = $this->prepareNewNumFacture($agence->getId());
            $facture->setNum(intval($newNum));

            $factureProduit = new FactureProduit();

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

        if ($f_auto_devise) {
            $devise = $this->getDoctrine()
                ->getRepository('AppBundle:Devise')
                ->find($f_auto_devise);
            
            $facture->setDevise($devise);
            $facture->setMontantConverti($f_auto_montant_converti);
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

        if ($f_recu) {
            $commande = $this->getDoctrine()
                ->getRepository('AppBundle:Commande')
                ->find($f_recu);

            $factureProduit->setCommande($commande);
        }

        $factureProduit->setFacture($facture);


        $em->persist($factureProduit);
        $em->flush();

        $details_list = explode(",", $list_id);

        // Suppression de tous les details
        foreach ($details_list as $old_id) {

            if ($old_id != "") {
                $old_detail = $this->getDoctrine()
                                    ->getRepository('AppBundle:FactureProduitDetails')
                                    ->find($old_id);

                $em->remove($old_detail);
                $em->flush();
            }

        }

        $f_libre = $request->request->get('f_libre');
        $f_designation = $request->request->get('f_designation');
        $f_produit = $request->request->get('f_produit');
        $f_prix = $request->request->get('f_prix');
        $f_qte = $request->request->get('f_qte');
        $f_montant = $request->request->get('f_montant');
        $f_remise_type_ligne = $request->request->get('f_remise_type_ligne');
        $f_remise_ligne = $request->request->get('f_remise_ligne');

        if (!empty($f_produit)) {
            foreach ($f_produit as $key => $value) {
                $detail = new FactureProduitDetails();

                $libre = $f_libre[$key];
                $designation = ($f_designation) ? $f_designation[$key] : '';
                $prix = $f_prix[$key];
                $qte = $f_qte[$key];
                $montant = $f_montant[$key];
                $remise_type_ligne = ($f_remise_type_ligne) ? $f_remise_type_ligne[$key] : null;
                $remise_ligne = ($f_remise_ligne) ? $f_remise_ligne[$key] : null;


                if ($libre == 1) {
                    $detail->setDesignation($designation);
                } else {
                    $variationProduit = $this->getDoctrine()
                        ->getRepository('AppBundle:VariationProduit')
                        ->find( $f_produit[$key] );

                    if ($f_type == 2) {
                        $variationProduit->setStock($variationProduit->getStock() - $qte);
                    }
                    
                    $detail->setVariationProduit($variationProduit);
                }

                $detail->setLibre($libre);
                $detail->setPrix($prix);
                $detail->setQte($qte);
                $detail->setMontant($montant);
                $detail->setTypeRemise($remise_type_ligne);
                $detail->setMontantRemise($remise_ligne);
                $detail->setFactureProduit($factureProduit);

                // var_dump("expression");die();

                $em->persist($detail);
                $em->flush();

            }
        }

        if ($f_is_credit == 1 && !$facture->getCredit()) {
            $this->saveCredit($facture);
        }

        return $this->redirectToRoute('facture_produit_show',array('id' => $facture->getId()));

    }

    public function saveCredit($facture)
    {

        $factureProduit  = $this->getDoctrine()
                        ->getRepository('AppBundle:FactureProduit')
                        ->findOneBy(array(
                            'facture' => $facture
                        ));

        $details = $this->getDoctrine()
                    ->getRepository('AppBundle:FactureProduitDetails')
                    ->findBy(array(
                        'factureProduit' => $factureProduit
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
            $creditDetails = new creditDetails();

            $type = 1;
            $variation = $detail->getVariationProduit();
            $qte = $detail->getQte();
            $prix = $detail->getPrix();
            $montant = $detail->getMontant();
            $type_remise = $detail->getTypeRemise();
            $montant_remise = $detail->getMontantRemise();

            $creditDetails->setType($type);
            $creditDetails->setVariationProduit($variation);
            $creditDetails->setQte($qte);
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

        $factureProduit  = $this->getDoctrine()
                        ->getRepository('AppBundle:FactureProduit')
                        ->findOneBy(array(
                            'facture' => $facture
                        ));

        $definitif = $this->getDoctrine()
                        ->getRepository('AppBundle:Facture')
                        ->findOneBy(array(
                            'proforma' => $facture
                        ));
 
        $details = $this->getDoctrine()
                    ->getRepository('AppBundle:FactureProduitDetails')
                    ->findBy(array(
                        'factureProduit' => $factureProduit
                    ));
        $produitsDetails = [];
        foreach ($details as $detail) {
            $detailsProduit = $this->getDoctrine()
                ->getRepository('AppBundle:FactureProduitDetails')
                ->getFactureProduitDetails($detail->getId());
            array_push($produitsDetails, $detailsProduit);
        }
        
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

        // $produits = $this->getDoctrine()
        //     ->getRepository('AppBundle:Produit')
        //     ->findBy(array(
        //         'agence' => $agence
        //     ));

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
                    
        // $variations = $this->getDoctrine()
        //         ->getRepository('AppBundle:VariationProduit')
        //         ->list($agence->getId());

        $devises = $this->getDoctrine()
                    ->getRepository('AppBundle:Devise')
                    ->findBy(array(
                        'agence' => $agence
                    ));


        $deviseEntrepot = $this->getDevise();


        $checkFactureBonCommande = $this->checkFactureBonCommande();

        return $this->render('FactureBundle:FactureProduit:show.html.twig', array(
            'deviseEntrepot' => $deviseEntrepot,
            'devises' => $devises,
            'agence' => $agence, 
            'facture' => $facture,
            'factureProduit' => $factureProduit,
            'details' => $details,
            // 'produits' => $produits,
            // 'variations' => $variations, 
            'clients' => $clients,
            'permissions' => $permissions,
            'print' => $print,
            'definitif' => $definitif,
            'produitsDetails' => $produitsDetails,
            'checkFactureBonCommande' => $checkFactureBonCommande,
        ));

        // return new JsonResponse($facture) ;


    } 

    public function pdfAction($id)
    {
        $facture  = $this->getDoctrine()
                        ->getRepository('AppBundle:Facture')
                        ->find($id);

        $factureProduit  = $this->getDoctrine()
                        ->getRepository('AppBundle:FactureProduit')
                        ->findOneBy(array(
                            'facture' => $facture
                        ));

        $details = $this->getDoctrine()
                    ->getRepository('AppBundle:FactureProduitDetails')
                    ->findBy(array(
                        'factureProduit' => $factureProduit
                    ));

        $produitsDetails = [];
        foreach ($details as $detail) {
            $detailsProduit = $this->getDoctrine()
                ->getRepository('AppBundle:FactureProduitDetails')
                ->getFactureProduitDetails($detail->getId());
            array_push($produitsDetails, $detailsProduit);
        }
                    

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $produits = $this->getDoctrine()
            ->getRepository('AppBundle:Produit')
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

        $template = $this->renderView('FactureBundle:FactureProduit:pdf.html.twig', array(
            'deviseEntrepot' => $deviseEntrepot,
            'agence' => $agence,
            'facture' => $facture,
            'factureProduit' => $factureProduit,
            'details' => $details,
            'produits' => $produits,
            'modelePdf' => $modelePdf,
            'produitsDetails' => $produitsDetails
        ));

        $html2pdf = $this->get('app.html2pdf');

        $html2pdf->create();

        return $html2pdf->generatePdf($template, "facture" . $facture->getId());

    }

    public function recuAction($recu)
    {

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $agence = $userAgence->getAgence();

        // $variations = $this->getDoctrine()
        //         ->getRepository('AppBundle:VariationProduit')
        //         ->list($agence->getId());

        // $results = $this->getDoctrine()
        //                 ->getRepository('AppBundle:Commande')
        //                 ->consultation($agence->getId(), intval($recu));

        // if (!empty($results)) { 
            // $commande = $results[0];

            $panniers = $this->getDoctrine()
            ->getRepository('AppBundle:Pannier')
        ->findBy(array(
            'commande' => intval($recu)
        ));

            $tpl = $this->renderView('FactureBundle:FactureProduit:recu.html.twig',array(
            // 'variations' => $variations,
            // 'commande' => $commande,
            'panniers' => $panniers
            ));

            return new JsonResponse(array(
                'tpl' => $tpl,
            ));
        // }

    }

    public function bonCommandeAction($id)
    {
        $facture  = $this->getDoctrine()
                        ->getRepository('AppBundle:Facture')
                        ->find($id);

        $factureProduit  = $this->getDoctrine()
                        ->getRepository('AppBundle:FactureProduit')
                        ->findOneBy(array(
                            'facture' => $facture
                        ));

        $details = $this->getDoctrine()
                    ->getRepository('AppBundle:FactureProduitDetails')
                    ->findBy(array(
                        'factureProduit' => $factureProduit
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

            $type = 1;
            $libre = $detail->getLibre();
            $variation = $detail->getVariationProduit();
            $qte = $detail->getQte();
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
            
            $panier->setVariationProduit($variation);
            $panier->setQte($qte);
            $panier->setPrix($prix);
            $panier->setTypeRemise($type_remise);
            $panier->setMontantRemise($montant_remise);
            $panier->setMontant($montant);
            $panier->setBonCommande($bonCommande);

            $em->persist($panier);
            $em->flush();

        }

        $factureProduit->setBonCommande($bonCommande);

        $em->persist($factureProduit);
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

        $factureProduit  = $this->getDoctrine()
                        ->getRepository('AppBundle:FactureProduit')
                        ->findOneBy(array(
                            'facture' => $facture
                        ));

        $details = $this->getDoctrine()
                    ->getRepository('AppBundle:FactureProduitDetails')
                    ->findBy(array(
                        'factureProduit' => $factureProduit
                    ));

        $factureDefinitif = new Facture();
        $factureProduitDefinitif = new FactureProduit();
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

        $factureProduitDefinitif->setFacture($factureDefinitif);

        $em->persist($factureProduitDefinitif);
        $em->flush();

        foreach ($details as $detail) {
            $detailDefinitif = new FactureProduitDetails();

            $libre = $detail->getLibre();
            $prix = $detail->getPrix();
            $qte = $detail->getQte();
            $montant = $detail->getMontant();
            $remise_type_ligne = $detail->getTypeRemise();
            $remise_ligne = $detail->getMontantRemise();

            if ($libre == 1) {
                $designation = $detail->getDesignation();
                $detailDefinitif->setDesignation($designation);
            } else {
                $variationProduit = $detail->getVariationProduit();
                $detailDefinitif->setVariationProduit($variationProduit);
            }

            $detailDefinitif->setLibre($libre);
            $detailDefinitif->setPrix($prix);
            $detailDefinitif->setQte($qte);
            $detailDefinitif->setMontant($montant);
            $detailDefinitif->setTypeRemise($remise_type_ligne);
            $detailDefinitif->setMontantRemise($remise_ligne);
            $detailDefinitif->setFactureProduit($factureProduitDefinitif);

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
