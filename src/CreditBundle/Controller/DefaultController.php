<?php

namespace CreditBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Credit;
use AppBundle\Entity\CreditDetails;
use AppBundle\Entity\PaiementCredit;
use AppBundle\Entity\Commande;
use AppBundle\Entity\Pannier;
class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CreditBundle:Default:index.html.twig');
    }

    public function addAction()
    {

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $variations = $this->getDoctrine()
                ->getRepository('AppBundle:VariationProduit')
                ->list($agence->getId());

        $clients = $this->getDoctrine()
            ->getRepository('AppBundle:Client')
            ->findBy(array(
                'agence' => $agence
            ));

        $devises = $this->getDoctrine()
                    ->getRepository('AppBundle:Devise')
                    ->findBy(array(
                        'agence' => $agence
                    ));

        $deviseEntrepot = $this->getDevise();

        return $this->render('CreditBundle:Default:add.html.twig', array(
            'agence' => $agence,
            'devises' => $devises,
            'variations' => $variations,
            'clients' => $clients,
            'userAgence' => $userAgence,
            'deviseEntrepot' => $deviseEntrepot,
        ));
    }

    public function saveAction(Request $request)
    {
        $id = $request->request->get('id');
        $montant_ht = $request->request->get('montant_ht');
        $remise_type = $request->request->get('remise_type');
        $remise = $request->request->get('remise');
        $montant_remise = $request->request->get('montant_remise');
        $tva_type = $request->request->get('tva_type');
        $tva = $request->request->get('tva');
        $montant_tva = $request->request->get('montant_tva');
        $montant_total = $request->request->get('montant_total');
        $lettre = $request->request->get('lettre');
        $date = $request->request->get('date');
        $client = $request->request->get('client');
        $statut = $request->request->get('statut');
        $date = \DateTime::createFromFormat('j/m/Y', $date);
        $lieu = $request->request->get('lieu');
        $devise = $request->request->get('devise');
        $montant_converti = $request->request->get('montant_converti');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        if ($id) {
            $credit = $this->getDoctrine()
                ->getRepository('AppBundle:Credit')
                ->find($id);
        } else {
            $credit = new Credit();
        }

        if ($client) {
            $client = $this->getDoctrine()
                    ->getRepository('AppBundle:Client')
                    ->find($client);
            $credit->setClient($client);
        }

        $credit->setHt($montant_ht);
        $credit->setRemiseType($remise_type);
        $credit->setRemise($remise);
        $credit->setMontantRemise($montant_remise);
        $credit->setTvaType($tva_type);
        $credit->setTva($tva);
        $credit->setMontantTva($montant_tva);
        $credit->setTotal($montant_total);
        $credit->setLettre($lettre);
        $credit->setDate($date);
        $credit->setLieu($lieu);
        $credit->setAgence($agence);
        $credit->setStatut($statut ? $statut : 1); // statut 1 : en cours

        if ($devise) {
            $devise = $this->getDoctrine()
                ->getRepository('AppBundle:Devise')
                ->find($devise);
            
            $credit->setDevise($devise);
            $credit->setMontantConverti($montant_converti);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($credit);
        $em->flush();

        $type_designation = $request->request->get('type_designation');
        $designations = $request->request->get('designation');
        $designation_autres = $request->request->get('designation_autre');
        $f_ps_qte = $request->request->get('f_ps_qte');
        $f_ps_periode = $request->request->get('f_ps_periode');
        $f_ps_duree = $request->request->get('f_ps_duree');
        $prixList = $request->request->get('prix');
        $remise_type_ligne = $request->request->get('remise_type_ligne');
        $remise_ligne = $request->request->get('remise_ligne');
        $montantList = $request->request->get('montant');


        $details = $this->getDoctrine()
                    ->getRepository('AppBundle:CreditDetails')
                    ->findBy(array(
                        'credit' => $credit
                    ));

        foreach ($details as $detail) {
            $em->remove($detail);
            $em->flush();
        }

        if (!empty($type_designation)) {
            foreach ($type_designation as $key => $value) {

                $detail = new CreditDetails();

                $type = $type_designation[$key];
                $designation = $designations[$key];
                $designation_autre = $designation_autres[$key];
                $qte = $f_ps_qte[$key];
                $periode = $f_ps_periode[$key];
                $duree = $f_ps_duree[$key];
                $prix = $prixList[$key];
                $type_remise = $remise_type_ligne[$key];
                $montant_remise = $remise_ligne[$key];
                $montant = $montantList[$key];

                $detail->setType($type);

                if ($type == 1) {
                    $variation = $this->getDoctrine()
                                        ->getRepository('AppBundle:VariationProduit')
                                        ->find( $designation );
                    $detail->setVariationProduit($variation);
                    $detail->setQte($qte);
                }

                if ($type == 2) {
                    $service = $this->getDoctrine()
                                        ->getRepository('AppBundle:Service')
                                        ->find( $designation );
                    $detail->setService($service);
                    $detail->setDuree($duree);
                    $detail->setPeriode($periode);

                }

                if ($type == 3) {
                    $detail->setDesignationAutre($designation_autre);
                    $detail->setQte($qte);
                }
                
                $detail->setPrix($prix);
                $detail->setTypeRemise($type_remise);
                $detail->setMontantRemise($montant_remise);
                $detail->setMontant($montant);
                $detail->setCredit($credit);

                $em->persist($detail);
                $em->flush();
            
            }
        }

        return new JsonResponse(array(
            'id' => $credit->getId()
        ));
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

        return $this->render('CreditBundle:Default:consultation.html.twig',array( 
            'userAgence' => $userAgence
        ));
    }

    public function encoursValidationAction()
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        return $this->render('CreditBundle:Default:encours-validation.html.twig',array(
            'userAgence' => $userAgence
        ));
    }

    public function listAction(Request $request)
    {

        $agence = $request->request->get('agence');
        $statut = $request->request->get('statut');
        $statut_paiement = $request->request->get('statut_paiement');
        $type_date = $request->request->get('type_date');
        $mois = $request->request->get('mois');
        $annee = $request->request->get('annee');
        $date_specifique = $request->request->get('date_specifique');
        $debut_date = $request->request->get('debut_date');
        $fin_date = $request->request->get('fin_date');
        $recherche_par = $request->request->get('recherche_par');
        $a_rechercher = $request->request->get('a_rechercher');
        
        $credits = $this->getDoctrine()
                        ->getRepository('AppBundle:Credit') 
                        ->consultation(  
                            $agence,
                            $statut,
                            $statut_paiement,  
                            0,
                            $type_date,
                            $mois,
                            $annee, 
                            $date_specifique,
                            $debut_date,
                            $fin_date,
                            $recherche_par,
                            $a_rechercher
                        );

        $data = array();
        $credit_payee = [] ;
        foreach ($credits as $credit) {
            $details = $this->getDoctrine()
                    ->getRepository('AppBundle:CreditDetails')
                    ->consultation($credit['id']);

            $paiements = $this->getDoctrine()
            ->getRepository('AppBundle:PaiementCredit') 
            ->getSommePayee($credit['id']);

            array_push($credit_payee,$paiements["m_payee"] ) ;
            
            $credit['details'] = null;

            if (!empty($details)) {
                $credit['details'] = $details;
            }

            array_push($data, $credit);
        }

        $agence = $this->getDoctrine()
                        ->getRepository('AppBundle:Agence')
                        ->find($agence);

        return $this->render('CreditBundle:Default:list.html.twig',array(
            'agence' => $agence,
            'credits' => $data,
            'credit_payee' => $credit_payee
        ));
        
    }

    public function showAction($id)
    {
        $credit = $this->getDoctrine()
                        ->getRepository('AppBundle:Credit')
                        ->find($id);

        $details = $this->getDoctrine()
                    ->getRepository('AppBundle:CreditDetails')
                    ->findBy(array(
                        'credit' => $credit
                    ));

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $variations = $this->getDoctrine()
                ->getRepository('AppBundle:VariationProduit')
                ->list($agence->getId());

        $services = $this->getDoctrine()
                ->getRepository('AppBundle:Service')
                ->getList($agence->getId());

        $clients = $this->getDoctrine()
            ->getRepository('AppBundle:Client')
            ->findBy(array(
                'agence' => $agence
            ));

        $devises = $this->getDoctrine()
                    ->getRepository('AppBundle:Devise')
                    ->findBy(array(
                        'agence' => $agence
                    ));

        $deviseEntrepot = $this->getDevise();

        return $this->render('CreditBundle:Default:show.html.twig',array(
            'credit' => $credit,
            'deviseEntrepot' => $deviseEntrepot,
            'devises' => $devises,
            'details' => $details,
            'clients' => $clients,
            'variations' => $variations,
            'services' => $services,
            'userAgence' => $userAgence,
            'agence' => $agence,
        ));
    }

    public function validationAction($id) 
    {
        $credit = $this->getDoctrine()
                        ->getRepository('AppBundle:Credit')
                        ->find($id);

        $credit->setStatut(2); // statut 2 : validé

        $em = $this->getDoctrine()->getManager();
        $em->persist($credit);
        $em->flush();

        $details = $this->getDoctrine()
                    ->getRepository('AppBundle:CreditDetails')
                    ->findBy(array(
                        'credit' => $credit
                    ));

        foreach ($details as $detail) {
            $type = $detail->getType();

            if ($type == 1) {
                /**
                 * Stock produit
                 */
                $variation = $detail->getVariationProduit();

                $produitEntrepot = $variation->getProduitEntrepot();

                $produit = $produitEntrepot->getProduit(); 

                $qte = $detail->getQte();

                /**
                 * Stock produit
                 */
                $produit->setStock( $produit->getStock() - $qte );
                $em->persist($produit);
                $em->flush();

                /**
                 * Stock produitEntrepot
                 */
                $produitEntrepot->setStock( $produitEntrepot->getStock() - $qte );
                $em->persist($produitEntrepot);
                $em->flush();

                /**
                 * Stock variation
                 */
                $variation->setStock( $variation->getStock() - $qte );
                $em->persist($variation);
                $em->flush();
            }
        }

        return new JsonResponse(array(
            'id' => $credit->getId()
        ));
    }

    public function paiementAction($id)
    {
        $credit = $this->getDoctrine()
                        ->getRepository('AppBundle:Credit')
                        ->find($id);

        $details = $this->getDoctrine()
                    ->getRepository('AppBundle:CreditDetails')
                    ->findBy(array(
                        'credit' => $credit
        ));

        $myVariationProduit = $this->getDoctrine()
            ->getRepository('AppBundle:FactureProduitDetails')
            ->findVariationByCredit($id);

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence()->getId();

        $variations = $this->getDoctrine()
                ->getRepository('AppBundle:VariationProduit')
                ->list($agence);

        $services = $this->getDoctrine()
                ->getRepository('AppBundle:Service')
                ->getList($agence);

        $clients = $this->getDoctrine()
            ->getRepository('AppBundle:Client')
            ->findBy(array(
                'agence' => $agence
            ));

        $paiements = $this->getDoctrine()
            ->getRepository('AppBundle:PaiementCredit')
            ->findBy(array(
                'credit' => $credit
            ));


        return $this->render('CreditBundle:Default:paiement.html.twig',array(
            'credit' => $credit,
            'details' => $details,
            'clients' => $clients,
            'variations' => $variations,
            'services' => $services,
            'userAgence' => $userAgence,
            'paiements' => $paiements,
            'myVariationProduit' => $myVariationProduit
        ));
    }

    public function payerAction(Request $request)
    {
        $date_paiement = $request->request->get('date_paiement');
        $montant = $request->request->get('montant');
        $id = $request->request->get('id');

        $credit = $this->getDoctrine()
                        ->getRepository('AppBundle:Credit')
                        ->find($id);

        $paiement = new PaiementCredit();

        $date = \DateTime::createFromFormat('j/m/Y', $date_paiement);

        $paiement->setMontant($montant);
        $paiement->setDate($date);
        $paiement->setCredit($credit);

        $em = $this->getDoctrine()->getManager();
        $em->persist($paiement);
        $em->flush();

        $detailsMontant = $this->detailsMontant($credit);

        if ($detailsMontant['reste'] <= 0) {
            $credit->setStatutPaiement(1);
            $em->persist($credit);
            $em->flush();

            $date = $credit->getDate();
            $agence = $credit->getAgence();

            /**
             * Commande
             */
            $commande = new Commande();

            $commande->setDate($date);
            $commande->setAgence($agence);

            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();

            $details = $this->getDoctrine()
                    ->getRepository('AppBundle:CreditDetails')
                    ->findBy(array(
                        'credit' => $credit
                    ));

            $montant_total = 0;

            foreach ($details as $detail) {
                $type = $detail->getType();
                $prix = $detail->getPrix();
                $total = $detail->getMontant();

                if ($type == 1) {
                    $panier = new Pannier();
                    
                    $variation = $detail->getVariationProduit();
                    $produitEntrepot = $variation->getProduitEntrepot();
                    $produit = $produitEntrepot->getProduit(); 
                    
                    $qte = $detail->getQte();
                    
                    $panier->setDate($date);
                    $panier->setQte($qte);
                    $panier->setPu($prix);
                    $panier->setTotal($total);
                    $panier->setVariationProduit($variation);
                    $panier->setCommande($commande);

                    $em->persist($panier);
                    $em->flush();

                    $montant_total += $total;
                }
            }

            $commande->setTotal($montant_total);

            $em->persist($commande);
            $em->flush();

            $credit->setCommande($commande);
        } 

        return new JsonResponse(array(
            'id' => $paiement->getId()
        ));

    }

    public function detailsMontant($credit)
    {
        $paye = 0;
        $reste = 0;

        $paiements = $this->getDoctrine()
            ->getRepository('AppBundle:PaiementCredit')
            ->findBy(array(
                'credit' => $credit
            ));

        foreach ($paiements as $paiement) {
            $paye += $paiement->getMontant();
        }

        $reste = ($credit->getTotal()) - $paye;

        return array(
            'paye' => $paye,
            'reste' => $reste,
        );

    }

    public function encoursPaiementAction()
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        return $this->render('CreditBundle:Default:encours-paiement.html.twig',array(
            'userAgence' => $userAgence 
        ));
    }

    public function payesAction()
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        return $this->render('CreditBundle:Default:payes.html.twig',array(
            'userAgence' => $userAgence
        ));
    }

    public function deleteAction($id)
    {
        $credit  = $this->getDoctrine()
                        ->getRepository('AppBundle:Credit')
                        ->find($id);

        $_credit_details = $this->getDoctrine()
                        ->getRepository('AppBundle:CreditDetails')
                        ->findBy(array(
                            'credit' => $credit
                        ));

        $facture = $this->getDoctrine()
                        ->getRepository('AppBundle:Facture')
                        ->findOneBy(array(
                            'credit' => $credit
                        ));

        $paiement_credit = $this->getDoctrine()
                                ->getRepository('AppBundle:PaiementCredit')
                                ->findOneBy(array(
                                    'credit' => $credit
                                ));


        $em = $this->getDoctrine()->getManager();

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

        for ($i=0; $i < count($_credit_details) ; $i++) {
            $em->remove($_credit_details[$i]);
            $em->flush();
        }

        if($paiement_credit){
            $em->remove($paiement_credit);
            $em->flush();
        }

        $em->remove($credit);
        $em->flush();

        return new JsonResponse(200);
    }

    public function pdfAction($id)
    {
        $credit  = $this->getDoctrine()
                        ->getRepository('AppBundle:Credit')
                        ->find($id);

        $details = $this->getDoctrine()
                    ->getRepository('AppBundle:CreditDetails')
                    ->findBy(array(
                        'credit' => $credit
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

        $modelePdf = $credit->getModelePdf();      


        $template = $this->renderView('CreditBundle:Default:pdf.html.twig', array(
            'credit' => $credit,
            'details' => $details,
            'modelePdf' => $modelePdf,
        ));

        $html2pdf = $this->get('app.html2pdf');

        $html2pdf->create();

        return $html2pdf->generatePdf($template, "credit" . $credit->getId());

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
