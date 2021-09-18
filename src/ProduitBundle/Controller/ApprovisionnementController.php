<?php

namespace ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Approvisionnement;
use AppBundle\Entity\Ravitaillement;
use AppBundle\Entity\PrixProduit;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApprovisionnementController extends Controller
{
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
	            	'agence' => $agence
	            ));

        return $this->render('ProduitBundle:Approvisionnement:add.html.twig', array(
        	'produits' => $produits
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
        $qteList = $request->request->get('qte');
        $prixList = $request->request->get('prix');
        $prixVenteList = $request->request->get('prix_vente');
        $totalList = $request->request->get('total');
        $expirerList = $request->request->get('expirer');
        $approId = $request->request->get('appro_id');

        if (!empty($produitList)) {
        	foreach ($produitList as $key => $value) {
                if($approId){
                    $approvisionnement = $this->getDoctrine()
                        ->getRepository('AppBundle:Approvisionnement')
                        ->find($approId);
                }else{
                    $approvisionnement = new Approvisionnement();
                }

                $produit = $this->getDoctrine()
                        ->getRepository('AppBundle:Produit')
                        ->find( $produitList[$key] );

        		$qte = $qteList[$key];
        		$prix = $prixList[$key];
        		$total = $totalList[$key];
                $vente = $prixVenteList[$key];
                $expirer = $expirerList[$key];
                $expirer = explode('/', $expirer);
                $expirer = $expirer[2].'-'.$expirer[1].'-'.$expirer[0];
                $expirer = new \DateTime($expirer);

                if($vente){
                    $approvisionnement->setDate($date);
                    $approvisionnement->setQte($qte);
                    $approvisionnement->setPrixAchat($prix);
                    $approvisionnement->setTotal($total);
                    $approvisionnement->setProduit($produit);
                    $approvisionnement->setRavitaillement($ravitaillement);
                    $approvisionnement->setDateExpiration($expirer);
                    $approvisionnement->setPrixVente($vente);
                    $approvisionnement->setDescription(' Approvisionnement du produit ' . $produit->getNom() . ' le ' . $date->format('d/m/Y') . ' ('. $qte .')' );

                    if(!$approId){
                      $em->persist($approvisionnement);
                    }

                    $prixProduit = $this->getDoctrine()
                                        ->getRepository('AppBundle:PrixProduit')
                                        ->findBy(array('prixVente' => $vente, 'agence' => $agence, 'produit' => $produit));
                    if(count($prixProduit) > 0){
                        $prixProduit = $prixProduit[0];
                        $qtePro = $prixProduit->getStock();
                        $prixProduit->setStock($qte + $qtePro);
                        $prixProduit->setAgence($agence);
                        $prixProduit->setProduit($produit);
                    }else{
                        $prixProduit = new PrixProduit();
                        $prixProduit->setPrixVente($vente);
                        $prixProduit->setStock($qte);
                        $prixProduit->setAgence($agence);
                        $prixProduit->setProduit($produit);
                        $em->persist($prixProduit);
                        $approvisionnement->setPrixProduit($prixProduit);
                    }
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

        return $this->render('ProduitBundle:Approvisionnement:consultation.html.twig',array(
            'userAgence' => $userAgence
        ));
    }

    public function listAction(Request $request)
    {

        $agence = $request->request->get('agence');

        $ravitaillements = $this->getDoctrine()
                    ->getRepository('AppBundle:Ravitaillement')
                    ->consultation($agence);

        $data = array();

        foreach ($ravitaillements as $ravitaillement) {
            $approvisionnements = $this->getDoctrine()
                    ->getRepository('AppBundle:Approvisionnement')
                    ->consultation($ravitaillement['id']);


            $ravitaillement['approvisionnements'] = null;

            if (!empty($approvisionnements)) {
                $ravitaillement['approvisionnements'] = $approvisionnements;
            }

            array_push($data, $ravitaillement);
        }

        return $this->render('ProduitBundle:Approvisionnement:list.html.twig',array(
            'ravitaillements' => $data
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
        $type = $request->request->get('type');


        $approvisionnements = $this->getDoctrine()
                ->getRepository('AppBundle:Approvisionnement')
                ->entreesSorties($produit_id, $type);

        return $this->render('ProduitBundle:Approvisionnement:entrees-sorties.html.twig',array(
            'approvisionnements' => $approvisionnements,
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
}
