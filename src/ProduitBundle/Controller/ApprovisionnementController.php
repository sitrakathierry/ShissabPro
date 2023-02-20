<?php

namespace ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Approvisionnement;
use AppBundle\Entity\Ravitaillement;
use AppBundle\Entity\VariationProduit;
use AppBundle\Entity\ProduitEntrepot;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApprovisionnementController extends Controller
{

    public function indexAction()
    {
        return $this->render('ProduitBundle:Approvisionnement:index.html.twig');
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

        $produits = $this->getDoctrine()
	    		->getRepository('AppBundle:Produit')
	            ->findBy(array(
	            	'agence' => $agence,
                    'is_delete' => NULL));
        
        $entrepots = $this->getDoctrine()
                ->getRepository('AppBundle:Entrepot')
                ->findBy(array(
                    'agence' => $agence
                ));

        $fournisseurs = $this->getDoctrine()
                ->getRepository('AppBundle:Fournisseur')
                ->findBy(array(
                    'agence' => $agence
        ));

        return $this->render('ProduitBundle:Approvisionnement:add_new.html.twig', array(
                'agence' => $agence,
                'produits' => $produits,
                'entrepots' => $entrepots,
                'fournisseurs' => $fournisseurs,
            ));
        
    }

    public function saveAction(Request $request)
    {
        // $id = $request->request->get('id');
        $montant_total = $request->request->get('montant_total');
        $date = $request->request->get('date');
        $date = \DateTime::createFromFormat('j/m/Y', $date);

        // $date = $date->format("j/m/Y");
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();
        $em = $this->getDoctrine()->getManager();
        // RAVITAILLEMENT
        $ravitaillement = new Ravitaillement();

        $ravitaillement->setTotal($montant_total);
        $ravitaillement->setDate($date);
        $ravitaillement->setAgence($agence);


        $em->persist($ravitaillement);
        $em->flush();
        // RAVITAILLEMENT

        $type_approList = $request->request->get('type_appro');
        $prix_produitList = $request->request->get('prix_produit');
        $qteList = $request->request->get('qte');
        $produitList = $request->request->get('produit');
        $entrepotList = $request->request->get('entrepot');
        $ref_produit = $request->request->get('ref_produit');
        $fournisseurList = $request->request->get('fournisseur');

        $totalList = $request->request->get('total');
        $expirerList = $request->request->get('expirer');
        $choix_nouveau = $request->request->get('choix_nouveau');

        $prixList = $request->request->get('prix');
        $chargeList = $request->request->get('charge');
        $prixRevientList = $request->request->get('prix_revient');
        $margeTypeList = $request->request->get('marge_type');
        $margeValeurList = $request->request->get('marge_valeur');

        $prixVenteList = $request->request->get('prix_vente');

        foreach ($type_approList as $key => $value) {
            $produit = $this->getDoctrine()
                        ->getRepository('AppBundle:Produit')
            ->find($produitList[$key]);

            $entrepot = $this->getDoctrine()
                ->getRepository('AppBundle:Entrepot')
            ->find($entrepotList[$key]);

            if ($type_approList[$key] == 1) {
                /**
                 * ProduitEntrepot Nouveau
                 */

                $produitEntrepot = $this->getDoctrine()
                    ->getRepository('AppBundle:ProduitEntrepot')
                    ->findOneBy(array(
                    'produit' => $produitList[$key],
                    'entrepot' => $entrepotList[$key],
                ));

                if (empty($produitEntrepot)) {
                    $produitEntrepot = new ProduitEntrepot();
                    $produitEntrepot->setStock($qteList[$key]);
                    $produitEntrepot->setProduit($produit);
                    $produitEntrepot->setEntrepot($entrepot);
                    $produitEntrepot->setIndice($ref_produit[$key]);
                } else {
                    $produitEntrepot->setStock($produitEntrepot->getStock() + $qteList[$key]);
                    if ($choix_nouveau[$key] == 1) { // Nouvelle indice
                        $produitEntrepot->setIndice($ref_produit[$key]);
                    }
                }
                $em->persist($produitEntrepot);
                $em->flush();

                if (empty($prix_produitList[$key])) {
                    // VRAIMENT NOUVEAU
                    $variationPrix = $this->getDoctrine()
                        ->getRepository('AppBundle:VariationProduit')
                        ->affichePrixProduit($produitList[$key]);

                    $present = False;
                    $idVar = 0;
                    foreach ($variationPrix as $varPrix) {
                        if ($prixVenteList[$key] == $varPrix['prix_vente']) {
                            $present = True;
                            $idVar = $varPrix['id'];
                            break;
                        }
                    }

                    if ($present) {
                        $variation = $this->getDoctrine()
                            ->getRepository('AppBundle:VariationProduit')
                        ->find($idVar);

                        $variation->setStock($variation->getStock() + $qteList[$key]);
                        $em->persist($variation);
                        $em->flush();

                        $approvisionnement = $this->getDoctrine()
                            ->getRepository('AppBundle:Approvisionnement')
                            ->findOneBy(array(
                            "variationProduit" => $idVar
                        ));

                        if (empty($approvisionnement)) {
                            $approvisionnement = new Approvisionnement();
                        }
                        if (isset($fournisseurList[$key]))
                            $approvisionnement->setFournisseurs(json_encode($fournisseurList[$key]));
                        $approvisionnement->setDate($date);
                        $approvisionnement->setQte($qteList[$key]);
                        $approvisionnement->setPrixAchat($prixList[$key]);
                        $approvisionnement->setCharge($chargeList[$key]);
                        $approvisionnement->setPrixRevient($prixRevientList[$key]);
                        $approvisionnement->setTotal($totalList[$key]);
                        $approvisionnement->setRavitaillement($ravitaillement);

                        if (!empty($expirerList[$key])) {
                            $approvisionnement->setDateExpiration($expirerList[$key]);
                        }
                        $approvisionnement->setDescription(' Approvisionnement du produit ' . $produit->getNom() . ' le ' . $date->format('d/m/Y') . ' (' . $qteList[$key] . ')');
                        $approvisionnement->setVariationProduit($variation);
                        $em->persist($approvisionnement);
                        $em->flush();
                    } else {
                        $variation = new VariationProduit();

                        $variation->setMargeType($margeTypeList[$key]);
                        $variation->setMargeValeur($margeValeurList[$key]);
                        $variation->setPrixVente($prixVenteList[$key]);
                        $variation->setStock($qteList[$key]);
                        $variation->setProduitEntrepot($produitEntrepot);

                        $em->persist($variation);
                        $em->flush();

                        $approvisionnement = new Approvisionnement();
                        if (isset($fournisseurList[$key]))
                            $approvisionnement->setFournisseurs(json_encode($fournisseurList[$key]));
                        $approvisionnement->setDate($date);
                        $approvisionnement->setQte($qteList[$key]);
                        $approvisionnement->setPrixAchat($prixList[$key]);
                        $approvisionnement->setCharge($chargeList[$key]);
                        $approvisionnement->setPrixRevient($prixRevientList[$key]);
                        $approvisionnement->setTotal($totalList[$key]);
                        $approvisionnement->setRavitaillement($ravitaillement);

                        if (!empty($expirerList[$key])) {
                            $approvisionnement->setDateExpiration($expirerList[$key]);
                        }
                        $approvisionnement->setDescription(' Approvisionnement du produit ' . $produit->getNom() . ' le ' . $date->format('d/m/Y') . ' (' . $qteList[$key] . ')');

                        $approvisionnement->setVariationProduit($variation);
                        $em->persist($approvisionnement);
                        $em->flush();
                    }
                } else {
                    /**
                     * Variation Produit
                     */
                    $variation = $this->getDoctrine()
                        ->getRepository('AppBundle:VariationProduit')
                        ->find($prix_produitList[$key]);

                    $variation->setStock($variation->getStock() + $qteList[$key]);
                    $em->persist($variation);
                    $em->flush();

                    /**
                     * Approvisionnement
                     */

                    $approvisionnement = $this->getDoctrine()
                    ->getRepository('AppBundle:Approvisionnement')
                    ->findOneBy(array(
                        "variationProduit" => $prix_produitList[$key]
                    ));
                    if (isset($fournisseurList[$key]))
                        $approvisionnement->setFournisseurs(json_encode($fournisseurList[$key]));
                    $approvisionnement->setDate($date);
                    $approvisionnement->setQte($qteList[$key]);
                    $approvisionnement->setTotal($totalList[$key]);
                    $approvisionnement->setRavitaillement($ravitaillement);

                    if (empty($expirerList[$key])) {
                        if (!empty($approvisionnement->getDate())) {
                            $dateProduit = $approvisionnement->getDate()->format('Y-m-d');
                            $dateNow = date("Y-m-d");
                            $timesProduit = strtotime($dateProduit);
                            $timesNow = strtotime($dateNow);
                            if ($timesProduit > $timesNow) {
                                $approvisionnement->setDateExpiration($dateNow);
                            }
                        }
                    } else {
                        $approvisionnement->setDateExpiration($expirerList[$key]);
                    }

                    $approvisionnement->setDescription(' Approvisionnement du produit ' . $produit->getNom() . ' le ' . $date->format('d/m/Y') . ' (' . $qteList[$key] . ')');
                    $em->persist($approvisionnement);
                    $em->flush();
                }
            } else if ($type_approList[$key] == 2) {
                /**
                 * Variation Produit
                 */
                $variation = $this->getDoctrine()
                    ->getRepository('AppBundle:VariationProduit')
                    ->find($prix_produitList[$key]);

                $variation->setStock($variation->getStock() + $qteList[$key]);
                $em->persist($variation);
                $em->flush();

                /**
                 * ProduitEntrepot
                 */
                $produitEntrepot = $this->getDoctrine()
                    ->getRepository('AppBundle:ProduitEntrepot')
                    ->find($variation->getProduitEntrepot());

                if (empty($produitEntrepot)) {
                    $produitEntrepot = new ProduitEntrepot();
                    $produitEntrepot->setStock($qteList[$key]);
                    $produitEntrepot->setProduit($produit);
                    $produitEntrepot->setEntrepot($entrepot);
                    $produitEntrepot->setIndice($ref_produit[$key]);
                } else {
                    $produitEntrepot->setStock($produitEntrepot->getStock() + $qteList[$key]);
                }

                $em->persist($produitEntrepot);
                $em->flush();

                /**
                 * Approvisionnement
                 */

                $approvisionnement = $this->getDoctrine()
                    ->getRepository('AppBundle:Approvisionnement')
                    ->findOneBy(array(
                        "variationProduit" => $prix_produitList[$key]
                    ));

                if (empty($approvisionnement)) {
                    $approvisionnement = new Approvisionnement();
                    $approvisionnement->setVariationProduit($variation);
                }

                if (isset($fournisseurList[$key]))
                    $approvisionnement->setFournisseurs(json_encode($fournisseurList[$key]));
                $approvisionnement->setDate($date);
                $approvisionnement->setQte($qteList[$key]);
                $approvisionnement->setTotal($totalList[$key]);
                $approvisionnement->setRavitaillement($ravitaillement);

                if (empty($expirerList[$key])) {
                    if (!empty($approvisionnement->getDate())) {
                        $dateProduit = $approvisionnement->getDate()->format("Y-m-d");
                        $dateNow = date("Y-m-d");
                        $timesProduit = strtotime($dateProduit);
                        $timesNow = strtotime($dateNow);
                        if ($timesProduit > $timesNow) {
                            $approvisionnement->setDateExpiration($dateNow);
                        }
                    }
                } else {
                    $approvisionnement->setDateExpiration($expirerList[$key]);
                }

                $approvisionnement->setDescription(' Approvisionnement du produit ' . $produit->getNom() . ' le ' . $date->format('d/m/Y') . ' (' . $qteList[$key] . ')');
                $em->persist($approvisionnement);
                $em->flush();
            }
        }

        return new JsonResponse(array(
            'id' => $approvisionnement->getId()
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

        $entrepots = $this->getDoctrine()
                ->getRepository('AppBundle:Entrepot')
                ->findBy(array(
                    'agence' => $agence
                ));

        $userEntrepot = $this->getDoctrine()
                    ->getRepository('AppBundle:UserEntrepot')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        return $this->render('ProduitBundle:Approvisionnement:consultation.html.twig',array(
            'userAgence' => $userAgence,
            'userEntrepot' => $userEntrepot,
            'entrepots' => $entrepots,
        ));
    }

    public function listAction(Request $request)
    {

        $agence = $request->request->get('agence');
        $entrepot = $request->request->get('entrepot');
        $type_date = $request->request->get('type_date');
        $mois = $request->request->get('mois');
        $annee = $request->request->get('annee');
        $date_specifique = $request->request->get('date_specifique');
        $debut_date = $request->request->get('debut_date');
        $fin_date = $request->request->get('fin_date');

        $ravitaillements = $this->getDoctrine()
                    ->getRepository('AppBundle:Ravitaillement')
                    ->consultation(
                        $agence,
                        $type_date,
                        $mois,
                        $annee,
                        $date_specifique,
                        $debut_date,
                        $fin_date
                    );

        $data = array();

        foreach ($ravitaillements as $ravitaillement) {
            $approvisionnements = $this->getDoctrine()
                    ->getRepository('AppBundle:Approvisionnement')
                    ->consultation(
                        $ravitaillement['id'],
                        $entrepot
                    );


            $ravitaillement['approvisionnements'] = null;

            if (!empty($approvisionnements)) {
                $ravitaillement['approvisionnements'] = $approvisionnements;
            }

            array_push($data, $ravitaillement);
        }

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        return $this->render('ProduitBundle:Approvisionnement:list.html.twig',array(
            'agence' => $agence,
            'ravitaillements' => $data,
        ));
        
    }

    public function DetailAction($approId)
    {
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
        $appro = $this->getDoctrine()
                    ->getRepository('AppBundle:Approvisionnement')
                    ->find($approId);
        $produit = $appro->getProduit();

        return $this->render('ProduitBundle:Approvisionnement:detail.html.twig', array(
            'produits' => $produits,
            'produit' => $produit,
            'appro' => $appro
        ));
    }

    public function entreesSortiesAction(Request $request)
    {
        $produit_id = $request->request->get('produit_id');
        $id_entrepot = $request->request->get('id_entrepot');
        $type = $request->request->get('type');

        $user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $agence = $userAgence->getAgence();


        $approvisionnements = $this->getDoctrine()
                ->getRepository('AppBundle:Approvisionnement')
                ->entreesSorties(
                    $produit_id, 
                    $type,
                    null,
                    $id_entrepot,
                    $agence->getId()
                );

        $produit = $this->getDoctrine()
                ->getRepository('AppBundle:Produit')
                ->find($produit_id);

        $deviseEntrepot = $this->getDevise();

        return $this->render('ProduitBundle:Approvisionnement:entrees-sorties.html.twig',array(
            'agence' => $agence,
            'deviseEntrepot' => $deviseEntrepot,
            'approvisionnements' => $approvisionnements,
            'produit' => $produit,
        ));
    }

    public function graphAction(Request $request)
    {
        $produit_id = $request->request->get('produit_id');
        $annee = $request->request->get('annee');

        $approvisionnements = $this->getDoctrine()
                ->getRepository('AppBundle:Approvisionnement')
                ->entreesSorties(
                    $produit_id,
                    0,
                    $annee
                );

        $dataAchat = array();
        $dataVente = array();
        $dataBenefice = array();
        $result = array();

        for ($i=0; $i < 12; $i++) { 
            $dataAchat[$i] = 0;
            $dataVente[$i] = 0;
            $dataBenefice[$i] = 0;
        }

        foreach ($approvisionnements as $approvisionnement) {
            $mois = intval( $approvisionnement['mois'] ) - 1;
            $total = $approvisionnement['total'];
            $type = $approvisionnement['type'];

            if ($type == 1) {
                $dataAchat[$mois] += $total;
            } else {
                $dataVente[$mois] += $total;
            }
        }

        array_push($result, array(
            'name' => 'ACHAT',
            'data' => $dataAchat
        ));

        array_push($result, array(
            'name' => 'VENTE',
            'data' => $dataVente
        ));

        foreach ($dataAchat as $mois => $achat) {
            $dataBenefice[$mois] = $dataVente[$mois] - $achat;
        }

        array_push($result, array(
            'name' => 'MARGE',
            'type' => 'spline',
            'data' => $dataBenefice,
            'color' => '#fd9597'
        ));

        return new JsonResponse( $result );
        
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
