<?php

namespace ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Approvisionnement;
use AppBundle\Entity\Ravitaillement;
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
        $totalList = $request->request->get('total');

        if (!empty($produitList)) {
        	foreach ($produitList as $key => $value) {
        		$approvisionnement = new Approvisionnement();

        		$produit = $this->getDoctrine()
		    		->getRepository('AppBundle:Produit')
		            ->find( $produitList[$key] );

        		$qte = $qteList[$key];
        		$prix = $prixList[$key];
        		$total = $totalList[$key];

        		$approvisionnement->setDate($date);
        		$approvisionnement->setQte($qte);
        		$approvisionnement->setPrixAchat($prix);
        		$approvisionnement->setTotal($total);
        		$approvisionnement->setProduit($produit);
        		$approvisionnement->setRavitaillement($ravitaillement);
        		$approvisionnement->setDescription(' Approvisionnement du produit ' . $produit->getNom() . ' le ' . $date->format('d/m/Y') . ' ('. $qte .')' );

        		$em->persist($approvisionnement);
        		$em->flush();

        		$produit->setStock( $produit->getStock() + $qte );
        		$em->persist($produit);
        		$em->flush();
        	
        	}
        }

        return new JsonResponse(array(
        	'id' => $approvisionnement->getId()
        ));
        

    }
}
