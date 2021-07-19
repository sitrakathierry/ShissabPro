<?php

namespace ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Produit;
use AppBundle\Entity\Approvisionnement;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ProduitBundle:Default:index.html.twig');
    }

    public function addAction()
    {
        return $this->render('ProduitBundle:Default:add.html.twig');
    }

    public function saveAction(Request $request)
    {
    	$id = $request->request->get('id');
    	$code = $request->request->get('code');
    	$qrcode = $request->request->get('qrcode');
    	$nom = $request->request->get('nom');
    	$description = $request->request->get('description');
    	$prix_achat = $request->request->get('prix_achat');
    	$prix_vente = $request->request->get('prix_vente');
    	$stock = $request->request->get('stock');
    	$dateCreation = new \DateTime('now');
    	$user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

    	$approvisionnement = null;
    	if ($id) {
    		$produit = $this->getDoctrine()
	    		->getRepository('AppBundle:Produit')
	            ->find($id);

    	} else {
    		$produit = new Produit();
	        $approvisionnement = new Approvisionnement();
    	}

    	$produit->setCodeProduit($code);
    	$produit->setQrcode($qrcode);
    	$produit->setNom($nom);
    	$produit->setDescription($description);
    	$produit->setPrixVente($prix_vente);
    	$produit->setStock($stock);
    	$produit->setDate($dateCreation);
    	$produit->setAgence($agence);

    	$em = $this->getDoctrine()->getManager();
        $em->persist($produit);
        $em->flush();

        if ($approvisionnement && $stock > 0) {
        	$approvisionnement = new Approvisionnement();

        	$approvisionnement->setDate($dateCreation);
        	$approvisionnement->setQte($stock);
        	$approvisionnement->setPrixAchat($prix_achat);
        	$approvisionnement->setTotal( ( $stock * $prix_achat ) );
        	$approvisionnement->setDescription(' Création du produit ' . $nom . ' le ' . $dateCreation->format('d/m/Y') );
        	$approvisionnement->setProduit($produit);

        	$em->persist($approvisionnement);
        	$em->flush();
        }

        return new JsonResponse(array(
        	'id' => $produit->getId()
        ));
    	
    }

    public function consultationAction()
    {
        $agences  = $this->getDoctrine()
                        ->getRepository('AppBundle:Agence')
                        ->findAll();

        $permission_user = $this->get('app.permission_user');
        $user = $this->getUser();
        $permissions = $permission_user->getPermissions($user);

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        return $this->render('ProduitBundle:Default:consultation.html.twig', array(
            'agences' => $agences,
            'userAgence' => $userAgence,
        ));
    }

    public function listAction(Request $request)
    {
        $agence = $request->request->get('agence');

        $produits  = $this->getDoctrine()
                        ->getRepository('AppBundle:Produit')
                        ->list($agence);

        return new JsonResponse($produits);
    }
}
