<?php

namespace CaisseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Commande;
use AppBundle\Entity\Pannier;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class VenteController extends Controller
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

        $prixProduit = $this->getDoctrine()
                            ->getRepository('AppBundle:PrixProduit')
                            ->findBy(array(
                                'agence' => $agence
                            ));

        return $this->render('CaisseBundle:Vente:add.html.twig', array(
        	'produits' => $produits,
            'prixProduits' => $prixProduit
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
        	$commande = $this->getDoctrine()
	    		->getRepository('AppBundle:Commande')
	            ->find($id);
        } else {
        	$commande = new Commande();
        }

        $commande->setTotal($montant_total);
        $commande->setDate($date);
        $commande->setAgence($agence);

        $em = $this->getDoctrine()->getManager();
        $em->persist($commande);
        $em->flush();

        $produitList = $request->request->get('produit');
        $qteList = $request->request->get('qte');
        $prixList = $request->request->get('prix');
        $totalList = $request->request->get('total');

        if (!empty($produitList)) {
        	foreach ($produitList as $key => $value) {

                $qte = $qteList[$key];
                $prix = $prixList[$key];
                $total = $totalList[$key];

        		$panier = new Pannier();

        		$prixProduit = $this->getDoctrine()
                		    		->getRepository('AppBundle:PrixProduit')
                		            ->find( $produitList[$key] );

                $approvisionnement = $this->getDoctrine()
                                          ->getRepository('AppBundle:Approvisionnement')
                                          ->findBy(array('prixProduit' => $prixProduit), array('dateExpiration' => 'ASC'));
                if(count($approvisionnement) > 0){
                    $qtes = $qte;
                    foreach ($approvisionnement as $key => $appro) {                         
                        $stock = $appro->getStockRestant();
                        if($appro->getStatus() == 0 && $qtes != 0 && $stock != 0){  
                            if($qtes > $stock){
                                $qtes = $qtes - $stock;
                                $restStock = $appro->setStockRestant($qtes);
                                if($qtes == 0)
                                    $appro->setStatus(1);
                            }else{                                
                                $qtes = $qtes - $stock;
                                $qtes = abs($qtes);
                                $restStock = $appro->setStockRestant($qtes);
                                if($qtes == 0)
                                    $appro->setStatus(1);
                                $qtes = 0;
                            }
                        }
                    }
                }

                $produit = $prixProduit->getProduit(); 

        		$panier->setDate($date);
        		$panier->setQte($qte);
        		$panier->setPu($prix);
        		$panier->setTotal($total);
        		$panier->setProduit($produit);
        		$panier->setCommande($commande);

        		$em->persist($panier);

                $produit->setStock( $produit->getStock() - $qte );
        		$prixProduit->setStock( $prixProduit->getStock() - $qte );
        		//$em->persist($produit);
        		$em->flush();
        	
        	}
        }

        return new JsonResponse(array(
        	'id' => $commande->getId()
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

        return $this->render('CaisseBundle:Vente:consultation.html.twig',array(
            'userAgence' => $userAgence
        ));
    }

    public function listAction(Request $request)
    {

        $agence = $request->request->get('agence');

        $commandes = $this->getDoctrine()
	                    ->getRepository('AppBundle:Commande')
	                    ->consultation($agence);

        $data = array();

        foreach ($commandes as $commande) {
            $panniers = $this->getDoctrine()
                    ->getRepository('AppBundle:Pannier')
                    ->consultation($commande['id']);


            $commande['panniers'] = null;

            if (!empty($panniers)) {
                $commande['panniers'] = $panniers;
            }

            array_push($data, $commande);
        }

        return $this->render('CaisseBundle:Vente:list.html.twig',array(
            'commandes' => $data
        ));
        
    }
}
