<?php

namespace BonCommandeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\BonCommande;
use AppBundle\Entity\PannierBon;
use AppBundle\Entity\Facture;
use AppBundle\Entity\FactureProduit;
use AppBundle\Entity\FactureProduitDetails;
use AppBundle\Entity\FactureService;
use AppBundle\Entity\FactureServiceDetails;
use AppBundle\Entity\FactureProduitService;
use AppBundle\Entity\FactureProduitServiceDetails;
use AppBundle\Entity\Commande;
use AppBundle\Entity\Pannier;
use AppBundle\Entity\BonLivraison;
use AppBundle\Entity\BonLivraisonDetails;
use BonCommandeBundle\Controller\BaseController;

class DefaultController extends BaseController
{
    public function indexAction()
    {
        return $this->render('BonCommandeBundle:Default:index.html.twig');
    }

    public function factureSansBonCommande($facture)
    {
        $bonCommande = null;

        if ($facture->getModele() == 1) {
            $factureProduit  = $this->getDoctrine()
                        ->getRepository('AppBundle:FactureProduit')
                        ->findOneBy(array(
                            'facture' => $facture
                        ));

            if ($factureProduit) {
                $bonCommande = $factureProduit->getBonCommande();
            }


        }

        if ($facture->getModele() == 2) {
            $factureService  = $this->getDoctrine()
                        ->getRepository('AppBundle:FactureService')
                        ->findOneBy(array(
                            'facture' => $facture
                        ));

            if ($factureService) {
                $bonCommande = $factureService->getBonCommande();
            }


        }

        if ($facture->getModele() == 3) {
            $factureProduitService  = $this->getDoctrine()
                        ->getRepository('AppBundle:FactureProduitService')
                        ->findOneBy(array(
                            'facture' => $facture
                        ));

            if ($factureProduitService) {
                $bonCommande = $factureProduitService->getBonCommande();
            }


        }
        
        if ($bonCommande) {
            return false;
        }

        return true;
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

        $factures = $this->getDoctrine()
            ->getRepository('AppBundle:Facture')
            ->findBy(array(
                'agence' => $agence,
                'modele' => [1,2,3]
            ));

        $factures = array_filter($factures, function ($facture){
            return $this->factureSansBonCommande($facture);
        });

        $deviseEntrepot = $this->getDevise();

        $checkBonCommandeProduit = $this->checkBonCommandeProduit();
        $checkBonCommandeService = $this->checkBonCommandeService();

        return $this->render('BonCommandeBundle:Default:add.html.twig', array(
            'agence' => $agence,
            'deviseEntrepot' => $deviseEntrepot,
            'variations' => $variations,
            'clients' => $clients,
            'factures' => $factures,
            'userAgence' => $userAgence,
            'checkBonCommandeProduit' => $checkBonCommandeProduit,
            'checkBonCommandeService' => $checkBonCommandeService,
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
        $facture = $request->request->get('facture');
        $modele = $request->request->get('modele');
        $date = \DateTime::createFromFormat('j/m/Y', $date);
        $lieu = $request->request->get('lieu');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        if ($id) {
            $bonCommande = $this->getDoctrine()
                ->getRepository('AppBundle:BonCommande')
                ->find($id);
        } else {
            $bonCommande = new BonCommande();
        }

        if ($client) {
            $client = $this->getDoctrine()
                    ->getRepository('AppBundle:Client')
                    ->find($client);
            $bonCommande->setClient($client);
        }

        $bonCommande->setHt($montant_ht);
        $bonCommande->setRemiseType($remise_type);
        $bonCommande->setRemise($remise);
        $bonCommande->setMontantRemise($montant_remise);
        $bonCommande->setTvaType($tva_type);
        $bonCommande->setTva($tva);
        $bonCommande->setMontantTva($montant_tva);
        $bonCommande->setTotal($montant_total);
        $bonCommande->setLettre($lettre);
        $bonCommande->setDate($date);
        $bonCommande->setLieu($lieu);
        $bonCommande->setAgence($agence);
        $bonCommande->setStatut($statut ? $statut : 1); // statut 1 : en cours

        $em = $this->getDoctrine()->getManager();
        $em->persist($bonCommande);
        $em->flush();

        if ($facture) {
            $facture = $this->getDoctrine()
                    ->getRepository('AppBundle:Facture')
                    ->find($facture);

            if ($modele == 1) {
                $factureProduit  = $this->getDoctrine()
                        ->getRepository('AppBundle:FactureProduit')
                        ->findOneBy(array(
                            'facture' => $facture
                        ));

                $factureProduit->setBonCommande($bonCommande);
                $em->persist($bonCommande);
                $em->flush();
            }

            if ($modele == 2) {
                $factureService  = $this->getDoctrine()
                        ->getRepository('AppBundle:FactureService')
                        ->findOneBy(array(
                            'facture' => $facture
                        ));

                $factureService->setBonCommande($bonCommande);
                $em->persist($bonCommande);
                $em->flush();
            }

            if ($modele == 3) {
                $factureProduitService  = $this->getDoctrine()
                        ->getRepository('AppBundle:FactureProduitService')
                        ->findOneBy(array(
                            'facture' => $facture
                        ));

                $factureProduitService->setBonCommande($bonCommande);
                $em->persist($bonCommande);
                $em->flush();
            }
        }

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


        $panniers = $this->getDoctrine()
                    ->getRepository('AppBundle:PannierBon')
                    ->findBy(array(
                        'bonCommande' => $bonCommande
                    ));

        foreach ($panniers as $pannier) {
            $em->remove($pannier);
            $em->flush();
        }

        if (!empty($type_designation)) {
            foreach ($type_designation as $key => $value) {

                $panier = new PannierBon();

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

                $panier->setType($type);

                if ($type == 1) {
                    $variation = $this->getDoctrine()
                                        ->getRepository('AppBundle:VariationProduit')
                                        ->find( $designation );
                    $panier->setVariationProduit($variation);
                    $panier->setQte($qte);
                }

                if ($type == 2) {
                    $service = $this->getDoctrine()
                                        ->getRepository('AppBundle:Service')
                                        ->find( $designation );
                    $panier->setService($service);
                    $panier->setDuree($duree);
                    $panier->setPeriode($periode);

                }

                if ($type == 3) {
                    $panier->setDesignationAutre($designation_autre);
                    $panier->setQte($qte);
                }
                
                $panier->setPrix($prix);
                $panier->setTypeRemise($type_remise);
                $panier->setMontantRemise($montant_remise);
                $panier->setMontant($montant);
                $panier->setBonCommande($bonCommande);

                $em->persist($panier);
                $em->flush();
            
            }
        }

        return new JsonResponse(array(
            'id' => $bonCommande->getId()
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

        return $this->render('BonCommandeBundle:Default:consultation.html.twig',array(
            'userAgence' => $userAgence
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

        return $this->render('BonCommandeBundle:Default:consultation_corbeille.html.twig',array(
            'userAgence' => $userAgence
        ));
    }

    public function listAction(Request $request)
    {

        $agence = $request->request->get('agence');
        $type_date = $request->request->get('type_date');
        $mois = $request->request->get('mois');
        $annee = $request->request->get('annee');
        $date_specifique = $request->request->get('date_specifique');
        $debut_date = $request->request->get('debut_date');
        $fin_date = $request->request->get('fin_date');

        $commandes = $this->getDoctrine()
                        ->getRepository('AppBundle:BonCommande')
                        ->consultation(
                            $agence,
                            0,
                            $type_date,
                            $mois,
                            $annee,
                            $date_specifique,
                            $debut_date,
                            $fin_date
                        );


        $data = array();

        foreach ($commandes as $commande) {
            $panniers = $this->getDoctrine()
                    ->getRepository('AppBundle:PannierBon')
                    ->consultation($commande['id']);


            $commande['panniers'] = null;

            if (!empty($panniers)) {
                $commande['panniers'] = $panniers;
            }

            array_push($data, $commande);
        }

        $agence = $this->getDoctrine()
                        ->getRepository('AppBundle:Agence')
                        ->find($agence);

        return $this->render('BonCommandeBundle:Default:list.html.twig',array(
            'agence' => $agence,
            'commandes' => $data,
        ));
        
    }

    public function listCorbeilleAction(Request $request)
    {

        $agence = $request->request->get('agence');
        $type_date = $request->request->get('type_date');
        $mois = $request->request->get('mois');
        $annee = $request->request->get('annee');
        $date_specifique = $request->request->get('date_specifique');
        $debut_date = $request->request->get('debut_date');
        $fin_date = $request->request->get('fin_date');

        $commandes = $this->getDoctrine()
                        ->getRepository('AppBundle:BonCommande')
                        ->consultationCorbeille(
                            $agence,
                            0,
                            $type_date,
                            $mois,
                            $annee,
                            $date_specifique,
                            $debut_date,
                            $fin_date
                        );


        $data = array();

        foreach ($commandes as $commande) {
            $panniers = $this->getDoctrine()
                    ->getRepository('AppBundle:PannierBon')
                    ->consultation($commande['id']);


            $commande['panniers'] = null;

            if (!empty($panniers)) {
                $commande['panniers'] = $panniers;
            }

            array_push($data, $commande);
        }

        $agence = $this->getDoctrine()
                        ->getRepository('AppBundle:Agence')
                        ->find($agence);

        return $this->render('BonCommandeBundle:Default:list_corbeille.html.twig',array(
            'agence' => $agence,
            'commandes' => $data,
        ));
        
    }

    public function showAction($id)
    {
        $commande = $this->getDoctrine()
                        ->getRepository('AppBundle:BonCommande')
                        ->find($id);

        $panniers = $this->getDoctrine()
                    ->getRepository('AppBundle:PannierBon')
                    ->findBy(array(
                        'bonCommande' => $commande
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

        $factureProduit = $this->getDoctrine()
                    ->getRepository('AppBundle:FactureProduit')
                    ->findOneBy(array(
                        'bonCommande' => $commande
                    ));

        $factureService = $this->getDoctrine()
                    ->getRepository('AppBundle:FactureService')
                    ->findOneBy(array(
                        'bonCommande' => $commande
                    ));

        $factureProduitService = $this->getDoctrine()
                    ->getRepository('AppBundle:FactureProduitService')
                    ->findOneBy(array(
                        'bonCommande' => $commande
                    ));

        $bonLivraison = $this->getDoctrine()
                    ->getRepository('AppBundle:BonLivraison')
                    ->findOneBy(array(
                        'bonCommande' => $commande
                    ));

        $deviseEntrepot = $this->getDevise();

        $checkBonCommandeProduit = $this->checkBonCommandeProduit();
        $checkBonCommandeService = $this->checkBonCommandeService();
        $checkBonCommandeBonLivraison = $this->checkBonCommandeBonLivraison();

        return $this->render('BonCommandeBundle:Default:show.html.twig',array(
            'agence' => $agence,
            'deviseEntrepot' => $deviseEntrepot,
            'commande' => $commande,
            'panniers' => $panniers,
            'clients' => $clients,
            'variations' => $variations,
            'services' => $services,
            'userAgence' => $userAgence,
            'factureProduit' => $factureProduit,
            'factureService' => $factureService,
            'factureProduitService' => $factureProduitService,
            'bonLivraison' => $bonLivraison,
            'checkBonCommandeProduit' => $checkBonCommandeProduit,
            'checkBonCommandeService' => $checkBonCommandeService,
            'checkBonCommandeBonLivraison' => $checkBonCommandeBonLivraison,
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

    public function pdfAction($id)
    {
        $bonCommande  = $this->getDoctrine()
                        ->getRepository('AppBundle:BonCommande')
                        ->find($id);

        $panniers = $this->getDoctrine()
                    ->getRepository('AppBundle:PannierBon')
                    ->findBy(array(
                        'bonCommande' => $bonCommande
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

        $modelePdf = $bonCommande->getModelePdf();   

        $deviseEntrepot = $this->getDevise();

        $template = $this->renderView('BonCommandeBundle:Default:pdf.html.twig', array(
            'deviseEntrepot' => $deviseEntrepot,
            'agence' => $agence,
            'bonCommande' => $bonCommande,
            'panniers' => $panniers,
            'modelePdf' => $modelePdf,
        ));

        $html2pdf = $this->get('app.html2pdf');

        $html2pdf->create();

        return $html2pdf->generatePdf($template, "bonCommande" . $bonCommande->getId());

    }

    public function validationAction($id)
    {
        $bonCommande = $this->getDoctrine()
                        ->getRepository('AppBundle:BonCommande')
                        ->find($id);

        $modele = $this->checkModele($bonCommande);

        if ($modele == 1) {

            $creerDefinitif = true;

            $factureProduit = $this->getDoctrine()
                    ->getRepository('AppBundle:FactureProduit')
                    ->findOneBy(array(
                        'bonCommande' => $bonCommande
                    ));

            if ($factureProduit) {
                $facture = $factureProduit->getFacture();

                if ($facture->getType() == 2) {
                    $creerDefinitif = false;
                } else {
                    $definitif = $this->getDoctrine()
                            ->getRepository('AppBundle:Facture')
                            ->findOneBy(array(
                                'proforma' => $facture
                            ));

                    if ($definitif) {
                        $creerDefinitif = false;
                    }
                }

            }

            if (!!$creerDefinitif) {
                $this->validationProduit($bonCommande, $factureProduit);
            }

        }

        if ($modele == 2) {

            $creerDefinitif = true;

            $factureService = $this->getDoctrine()
                    ->getRepository('AppBundle:FactureService')
                    ->findOneBy(array(
                        'bonCommande' => $bonCommande
                    ));

            if ($factureService) {
                $facture = $factureService->getFacture();

                if ($facture->getType() == 2) {
                    $creerDefinitif = false;
                } else {
                    $definitif = $this->getDoctrine()
                            ->getRepository('AppBundle:Facture')
                            ->findOneBy(array(
                                'proforma' => $facture
                            ));

                    if ($definitif) {
                        $creerDefinitif = false;
                    }
                }

            }

            if (!!$creerDefinitif) {
                $this->validationService($bonCommande, $factureService);
            }

        }

        if ($modele == 3) {

            $creerDefinitif = true;

            $factureProduitService = $this->getDoctrine()
                    ->getRepository('AppBundle:FactureProduitService')
                    ->findOneBy(array(
                        'bonCommande' => $bonCommande
                    ));

            if ($factureProduitService) {
                $facture = $factureProduitService->getFacture();

                if ($facture->getType() == 2) {
                    $creerDefinitif = false;
                } else {
                    $definitif = $this->getDoctrine()
                            ->getRepository('AppBundle:Facture')
                            ->findOneBy(array(
                                'proforma' => $facture
                            ));

                    if ($definitif) {
                        $creerDefinitif = false;
                    }
                }

            }

            if (!!$creerDefinitif) {
                $this->validationProduitService($bonCommande, $factureProduitService);
            }

        }


        $bonCommande->setStatut(2); // statut 2 : validé

        $em = $this->getDoctrine()->getManager();
        $em->persist($bonCommande);
        $em->flush();

        return new JsonResponse(array(
            'id' => $bonCommande->getId()
        ));
    }

    public function checkModele($bonCommande)
    {
        $panniersProduit = $this->getDoctrine()
                    ->getRepository('AppBundle:PannierBon')
                    ->findOneBy(array(
                        'bonCommande' => $bonCommande,
                        'type' => 1
                    ));

        $panniersService = $this->getDoctrine()
                    ->getRepository('AppBundle:PannierBon')
                    ->findOneBy(array(
                        'bonCommande' => $bonCommande,
                        'type' => 2
                    ));

        if ($panniersProduit && $panniersService) {
            return 3;
        } else {
            if ($panniersProduit) {
                return 1;
            }

            if ($panniersService) {
                return 2;
            }
        }
    }

    public function validationProduit($bonCommande, $proforma = null)
    {
        $panniers = $this->getDoctrine()
                    ->getRepository('AppBundle:PannierBon')
                    ->findBy(array(
                        'bonCommande' => $bonCommande
                    ));

        $f_type = 2;
        $f_model = 1;
        $f_client = $bonCommande->getClient();
        $f_date = $bonCommande->getDate();
        $descr = 'Bon de commande N° ' . $bonCommande->getRecu();
        $montant = $bonCommande->getTotal();
        $f_remise_type = $bonCommande->getRemiseType();
        $f_remise = $bonCommande->getRemise();
        $remise = $bonCommande->getMontantRemise();
        $total = $bonCommande->getTotal();
        $somme = '';
        $agence = $bonCommande->getAgence();
        $client = $bonCommande->getClient();
        $dateCreation = new \DateTime('now');

        /**
         * Commande
         */
        
        $commande = new Commande();

        $commande->setTotal($total);
        $commande->setDate($dateCreation);
        $commande->setAgence($agence);

        $em = $this->getDoctrine()->getManager();
        $em->persist($commande);
        $em->flush();

        /**
         * Facture
         */
        $facture = new Facture();
        $newNum = $this->prepareNewNumFacture($agence->getId());
        $facture->setNum(intval($newNum));

        $factureProduit = new FactureProduit();

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
        $facture->setDateCreation($dateCreation);
        $facture->setDate($dateCreation);
        $facture->setAgence($agence);

        if ($proforma) {
            $facture->setProforma($proforma->getFacture());
            $facture->setNum($proforma->getFacture()->getNum());
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($facture);
        $em->flush();

        $factureProduit->setCommande($commande);
        $factureProduit->setFacture($facture);

        $em->persist($factureProduit);
        $em->flush();


        foreach ($panniers as $detail) {

            /**
             * Pannier
             */

            $qte = $detail->getQte();
            $prix = $detail->getPrix();
            $total = $detail->getMontant();
            $variation = $detail->getVariationProduit();

            if ($variation) {
                $produitEntrepot = $variation->getProduitEntrepot(); 
                $produit = $produitEntrepot->getProduit(); 
            }

            $designation = $detail->getDesignationAutre();

            if ($variation) {
                $panier = new Pannier();
                $panier->setDate($dateCreation);
                $panier->setQte($qte);
                $panier->setPu($prix);
                $panier->setTotal($total);
                $panier->setVariationProduit($variation);
                $panier->setCommande($commande);

                $em->persist($panier);
                $em->flush();
            }


            /**
             * FactureProduitDetails
             */
            $f_p_detail = new FactureProduitDetails();

            $libre = (!$variation) ? 1 : 0;
            $remise_type_ligne = $detail->getTypeRemise();
            $remise_ligne = $detail->getMontantRemise();

            if ($variation) {
                $f_p_detail->setVariationProduit($variation);
            } else {
                $f_p_detail->setDesignation($designation);
            }
            $f_p_detail->setLibre($libre);
            $f_p_detail->setPrix($prix);
            $f_p_detail->setQte($qte);
            $f_p_detail->setMontant($total);
            $f_p_detail->setTypeRemise($remise_type_ligne);
            $f_p_detail->setMontantRemise($remise_ligne);
            $f_p_detail->setFactureProduit($factureProduit);

            $em->persist($f_p_detail);
            $em->flush();

            if ($variation) {
                /**
                 * Stock produit entrepot
                 */
                $produitEntrepot->setStock( $produitEntrepot->getStock() - $qte );
                $em->persist($produitEntrepot);
                $em->flush();

                /**
                 * Stock produit
                 */
                $produit->setStock( $produit->getStock() - $qte );
                $em->persist($produit);
                $em->flush();

                /**
                 * Stock variation
                 */
                $variation->setStock( $variation->getStock() - $qte );
                $em->persist($variation);
                $em->flush();
            }

        }

        $bonCommande->setCommande($commande);

        $em->persist($bonCommande);
        $em->flush();

        return new JsonResponse(array(
            'id' => $bonCommande->getId()
        ));
        
    }

    public function validationService($bonCommande, $proforma = null)
    {

        $panniers = $this->getDoctrine()
                    ->getRepository('AppBundle:PannierBon')
                    ->findBy(array(
                        'bonCommande' => $bonCommande
                    ));

        $f_type = 2;
        $f_model = 2;
        $f_client = $bonCommande->getClient();
        $f_date = $bonCommande->getDate();
        $descr = 'Bon de commande N° ' . $bonCommande->getRecu();
        $montant = $bonCommande->getTotal();
        $f_remise_type = $bonCommande->getRemiseType();
        $f_remise = $bonCommande->getRemise();
        $remise = $bonCommande->getMontantRemise();
        $total = $bonCommande->getTotal();
        $somme = '';
        $agence = $bonCommande->getAgence();
        $client = $bonCommande->getClient();
        $dateCreation = new \DateTime('now');

        $facture = new Facture();
        $newNum = $this->prepareNewNumFacture($agence->getId());
        $facture->setNum(intval($newNum));

        $factureService = new FactureService();

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
        $facture->setDateCreation($dateCreation);
        $facture->setDate($dateCreation);
        $facture->setAgence($agence);

        if ($proforma) {
            $facture->setProforma($proforma->getFacture());
            $facture->setNum($proforma->getFacture()->getNum());
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($facture);
        $em->flush();

        $factureService->setFacture($facture);
        $factureService->setBonCommande($bonCommande);

        $em->persist($factureService);
        $em->flush();

        foreach ($panniers as $detail) {

            /**
             * FactureServiceDetails
             */
            $f_p_detail = new FactureServiceDetails();

            $duree = $detail->getDuree();
            $periode = $detail->getPeriode();
            $prix = $detail->getPrix();
            $total = $detail->getMontant();
            $service = $detail->getService();
            $libre = (!$service) ? 1 : 0;
            $remise_type_ligne = $detail->getTypeRemise();
            $remise_ligne = $detail->getMontantRemise();

            if ($service) {
                $f_p_detail->setService($service);
            } else {
                $f_p_detail->setDesignation($designation);
            }

            // $f_p_detail->setService($service);
            $f_p_detail->setLibre($libre);
            $f_p_detail->setPrix($prix);
            $f_p_detail->setDuree($duree);
            $f_p_detail->setPeriode($periode);
            $f_p_detail->setMontant($total);
            $f_p_detail->setTypeRemise($remise_type_ligne);
            $f_p_detail->setMontantRemise($remise_ligne);
            $f_p_detail->setFactureService($factureService);

            $em->persist($f_p_detail);
            $em->flush();
          
        }

        return new JsonResponse(array(
            'id' => $bonCommande->getId()
        ));
    
    }

    public function validationProduitService($bonCommande, $proforma)
    {
        $panniers = $this->getDoctrine()
                    ->getRepository('AppBundle:PannierBon')
                    ->findBy(array(
                        'bonCommande' => $bonCommande
                    ));

        $f_type = 2;
        $f_model = 3;
        $f_client = $bonCommande->getClient();
        $f_date = $bonCommande->getDate();
        $descr = 'Bon de commande N° ' . $bonCommande->getRecu();
        $montant = $bonCommande->getTotal();
        $f_remise_type = $bonCommande->getRemiseType();
        $f_remise = $bonCommande->getRemise();
        $remise = $bonCommande->getMontantRemise();
        $total = $bonCommande->getTotal();
        $somme = '';
        $agence = $bonCommande->getAgence();
        $client = $bonCommande->getClient();
        $dateCreation = new \DateTime('now');

        /**
         * Commande
         */
        
        $commande = new Commande();

        $commande->setDate($dateCreation);
        $commande->setAgence($agence);

        $em = $this->getDoctrine()->getManager();
        $em->persist($commande);
        $em->flush();

        /**
         * Facture
         */
        $facture = new Facture();
        $newNum = $this->prepareNewNumFacture($agence->getId());
        $facture->setNum(intval($newNum));

        $factureProduitService = new FactureProduitService();

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
        $facture->setDateCreation($dateCreation);
        $facture->setDate($dateCreation);
        $facture->setAgence($agence);

        if ($proforma) {
            $facture->setProforma($proforma->getFacture());
            $facture->setNum($proforma->getFacture()->getNum());
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($facture);
        $em->flush();

        $factureProduitService->setCommande($commande);
        $factureProduitService->setFacture($facture);
        $factureProduitService->setBonCommande($bonCommande);

        $em->persist($factureProduitService);
        $em->flush();

        foreach ($panniers as $detail) {

            /**
             * Pannier
             */

            $type = $detail->getType();
            $prix = $detail->getPrix();
            $total = $detail->getMontant();

            if ($type == 1) {
                
                $variation = $detail->getVariationProduit();
                $qte = $detail->getQte();
                $designation = $detail->getDesignationAutre();

                if ($variation) {
                    $panier = new Pannier();
                    
                    $produitEntrepot = $variation->getProduitEntrepot(); 
                    $produit = $produitEntrepot->getProduit(); 
                    
                    $panier->setDate($dateCreation);
                    $panier->setQte($qte);
                    $panier->setPu($prix);
                    $panier->setTotal($total);
                    $panier->setVariationProduit($variation);
                    $panier->setCommande($commande);

                    $em->persist($panier);
                    $em->flush();
                }

            }

            /**
             * FactureProduitServiceDetails
             */
            $f_p_detail = new FactureProduitServiceDetails();

            $libre = 0;
            $remise_type_ligne = $detail->getTypeRemise();
            $remise_ligne = $detail->getMontantRemise();

            if ($type == 1) {
                $f_p_detail->setVariationProduit($variation);
                $f_p_detail->setQte($qte);
            } else {
                $service = $detail->getService();
                $duree = $detail->getDuree();
                $periode = $detail->getPeriode();

                $f_p_detail->setService($service);
                $f_p_detail->setDuree($duree);
                $f_p_detail->setPeriode($periode);
            }

            $f_p_detail->setType($type);
            $f_p_detail->setPrix($prix);
            $f_p_detail->setMontant($total);
            $f_p_detail->setTypeRemise($remise_type_ligne);
            $f_p_detail->setMontantRemise($remise_ligne);
            $f_p_detail->setFactureProduitService($factureProduitService);

            $em->persist($f_p_detail);
            $em->flush();

            if ($type == 1) {

                if ($variation) {
                    /**
                     * Stock produit entrepot
                     */
                    $produitEntrepot->setStock( $produitEntrepot->getStock() - $qte );
                    $em->persist($produitEntrepot);
                    $em->flush();

                    /**
                     * Stock produit
                     */
                    $produit->setStock( $produit->getStock() - $qte );
                    $em->persist($produit);
                    $em->flush();

                    /**
                     * Stock variation
                     */
                    $variation->setStock( $variation->getStock() - $qte );
                    $em->persist($variation);
                    $em->flush();
                }

            }

        }

        return new JsonResponse(array(
            'id' => $bonCommande->getId()
        ));
    }

    public function livraisonAction($id)
    {
        $commande = $this->getDoctrine()
                        ->getRepository('AppBundle:BonCommande')
                        ->find($id);

        $panniers = $this->getDoctrine()
                    ->getRepository('AppBundle:PannierBon')
                    ->findBy(array(
                        'bonCommande' => $commande
                    ));

        $client = $commande->getClient();
        $date = $commande->getDate();
        $agence = $commande->getAgence();

        $bonLivraison = new BonLivraison();

        $bonLivraison->setClient($client);

        $bonLivraison->setDate($date);
        $bonLivraison->setAgence($agence);
        $bonLivraison->setBonCommande($commande);
        $bonLivraison->setStatut(1); // statut 1 : en cours

        $em = $this->getDoctrine()->getManager();
        $em->persist($bonLivraison);
        $em->flush();

        foreach ($panniers as $pannier) {
            
            $type = $pannier->getType();
            $variation = $pannier->getVariationProduit();
            $service = $pannier->getService();
            $qte = $pannier->getQte();
            $periode = $pannier->getPeriode();
            $duree = $pannier->getDuree();

            $detail = new BonLivraisonDetails();

            $detail->setType($type);

            if ($type == 1) {
                $detail->setVariationProduit($variation);
                $detail->setQte($qte);
            }

            if ($type == 2) {
                $detail->setService($service);
                $detail->setDuree($duree);
                $detail->setPeriode($periode);

            }

            $detail->setBonLivraison($bonLivraison);

            $em->persist($detail);
            $em->flush();

        }

        return new JsonResponse(array(
            'id' => $bonLivraison->getId()
        ));

    }

    public function factureAction($facture)
    {
        $facture  = $this->getDoctrine()
                        ->getRepository('AppBundle:Facture')
                        ->find($facture);

        $modele = $facture->getModele();

        if ($modele == 1) {
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

            $tpl = $this->renderView('BonCommandeBundle:Facture:produit.html.twig',array(
                'details' => $details,
            ));
        }

        if ($modele == 2) {
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

            $tpl = $this->renderView('BonCommandeBundle:Facture:service.html.twig',array(
                'details' => $details,
            ));
        }

        if ($modele == 3) {
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

            $tpl = $this->renderView('BonCommandeBundle:Facture:produitservice.html.twig',array(
                'details' => $details,
            ));
        }

        return new JsonResponse(array(
            'tpl' => $tpl,
            'client_id' => $facture->getClient()->getNumPolice(),
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
