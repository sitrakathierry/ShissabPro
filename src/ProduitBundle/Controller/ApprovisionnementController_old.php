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

        return $this->render('ProduitBundle:Approvisionnement:add.html.twig', array(
        	'agence' => $agence,
            'produits' => $produits,
            'entrepots' => $entrepots,
            'fournisseurs' => $fournisseurs,
        ));
    }

    public function saveAction(Request $request)
    {
        $id = $request->request->get('id');
        $montant_total = $request->request->get('montant_total');
        $date = $request->request->get('date');
        $date = \DateTime::createFromFormat('j/m/Y', $date);
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        if ($id) {
            $ravitaillement = $this->getDoctrine()
                ->getRepository('AppBundle:Ravitaillement')
                ->find($id);
        } else {
            $ravitaillement = new Ravitaillement();
        }
        
        $ravitaillement->setTotal($montant_total);
        $ravitaillement->setDate($date);
        $ravitaillement->setAgence($agence);

        $em = $this->getDoctrine()->getManager();
        $em->persist($ravitaillement);
        $em->flush();

        $produitList = $request->request->get('produit');
        $entrepotList = $request->request->get('entrepot');
        $qteList = $request->request->get('qte');
        $fournisseurList = $request->request->get('fournisseur');
        $prixList = $request->request->get('prix');
        $chargeList = $request->request->get('charge');
        $prixRevientList = $request->request->get('prix_revient');
        $margeTypeList = $request->request->get('marge_type');
        $margeValeurList = $request->request->get('marge_valeur');
        $prixVenteList = $request->request->get('prix_vente');
        $totalList = $request->request->get('total');
        $expirerList = $request->request->get('expirer');
        $approId = $request->request->get('appro_id');

        if (!empty($produitList)) {
            foreach ($produitList as $key => $value) {

                $produit = $this->getDoctrine()
                        ->getRepository('AppBundle:Produit')
                        ->find( $produitList[$key] );

                $entrepot = $entrepotList[$key];
                $qte = $qteList[$key];
                $prix = $prixList[$key];
                $prixRevient = $prixRevientList[$key];
                $charge = $chargeList[$key];
                $margeType = $margeTypeList[$key];
                $margeValeur = $margeValeurList[$key];
                $fournisseur = $fournisseurList[$key];
                $total = $totalList[$key];
                $vente = $prixVenteList[$key];
                $expirer = $expirerList[$key];
                $expirer = \DateTime::createFromFormat('j/m/Y', $expirer);

                $entrepot = $this->getDoctrine()
                        ->getRepository('AppBundle:Entrepot')
                        ->find($entrepot);

                if($vente){

                    /**
                     * ProduitEntrepot
                     */
                    $produitEntrepot = $this->getDoctrine()
                                        ->getRepository('AppBundle:ProduitEntrepot')
                                        ->findOneBy(array(
                                            'produit' => $produit,
                                            'entrepot' => $entrepot,
                                        ));

                    if (!$produitEntrepot) {
                        $produitEntrepot = new ProduitEntrepot();
                    }

                    $produitEntrepot->setStock( $produitEntrepot->getStock() + $qte );
                    $produitEntrepot->setProduit($produit);
                    $produitEntrepot->setEntrepot($entrepot);

                    $em->persist($produitEntrepot);
                    $em->flush();

                    /**
                     * VariationProduit
                     */
                    $variation = $this->getDoctrine()
                                        ->getRepository('AppBundle:VariationProduit')
                                        ->findOneBy(array(
                                            'prixVente' => $vente, 
                                            'produitEntrepot' => $produitEntrepot
                                        ));
                    if ($variation) {
                        $variation->setStock( $qte + $variation->getStock() );
                    } else {
                        $variation = new VariationProduit();

                        $variation->setPrixVente($vente);
                        $variation->setMargeType($margeType);
                        $variation->setMargeValeur($margeValeur);
                        $variation->setPrixVente($vente);
                        $variation->setStock($qte);
                        $variation->setProduitEntrepot($produitEntrepot);
                    }

                    $em->persist($variation);
                    $em->flush();

                    /**
                     * Approvisionnement
                     */
                    if($approId){
                        $approvisionnement = $this->getDoctrine()
                            ->getRepository('AppBundle:Approvisionnement')
                            ->find($approId);
                    }else{
                        $approvisionnement = new Approvisionnement();
                    }

                    $approvisionnement->setFournisseurs( json_encode($fournisseur) );
                    $approvisionnement->setDate($date);
                    $approvisionnement->setQte($qte);
                    $approvisionnement->setPrixAchat($prix);
                    $approvisionnement->setCharge($charge);
                    $approvisionnement->setPrixRevient($prixRevient);
                    $approvisionnement->setTotal($total);
                    $approvisionnement->setRavitaillement($ravitaillement);

                    if ($expirer) {
                        $approvisionnement->setDateExpiration($expirer);
                    }

                    $approvisionnement->setDescription(' Approvisionnement du produit ' . $produit->getNom() . ' le ' . $date->format('d/m/Y') . ' ('. $qte .')' );


                    $approvisionnement->setVariationProduit($variation);
                    $em->persist($approvisionnement);
                    $em->flush();
                    
                    $produit->setStock( $produit->getStock() + $qte );
                    $em->persist($produit);
                    $em->flush();
                }
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
