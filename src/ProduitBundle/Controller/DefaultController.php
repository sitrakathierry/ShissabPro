<?php

namespace ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Produit;
use AppBundle\Entity\Approvisionnement;
use AppBundle\Entity\Ravitaillement;
use AppBundle\Entity\PrixProduit;
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
        $produit_image = $request->request->get('produit_image');
        $unite = $request->request->get('unite');
        $stock_alerte = $request->request->get('stock_alerte');
        $expirer = $request->request->get('expirer');
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
    	    $produit->setDate($dateCreation);
    	    $produit->setStock($stock);
    	}

    	$produit->setCodeProduit($code);
    	$produit->setQrcode($qrcode);
    	$produit->setNom($nom);
    	$produit->setDescription($description);
    	$produit->setPrixVente($prix_vente);
    	$produit->setAgence($agence);
        $produit->setImage($produit_image);
        $produit->setUnite($unite);
        $produit->setStockAlerte($stock_alerte);

    	$em = $this->getDoctrine()->getManager();
        $em->persist($produit);
        $em->flush();

        if ($approvisionnement && $stock > 0) {

            /**
             * Ravitaillement
             */
            $ravitaillement = new Ravitaillement();

            $ravitaillement->setDate($dateCreation);
            $ravitaillement->setTotal( ( $stock * $prix_achat ) );
            $ravitaillement->setAgence( $agence );

            $em->persist($ravitaillement);
            $em->flush();


            /**
             * Approvisionnement
             */
            $expirer = explode('/', $expirer);
            $expirer = $expirer[2].'-'.$expirer[1].'-'.$expirer[0];
            $expirer = new \DateTime($expirer);

        	$approvisionnement = new Approvisionnement();

        	$approvisionnement->setDate($dateCreation);
        	$approvisionnement->setQte($stock);
        	$approvisionnement->setPrixAchat($prix_achat);
        	$approvisionnement->setTotal( ( $stock * $prix_achat ) );
        	$approvisionnement->setDescription(' Création du produit ' . $nom . ' le ' . $dateCreation->format('d/m/Y') . ' ('. $stock . ' ' . $unite .')' );
        	$approvisionnement->setProduit($produit);
            $approvisionnement->setRavitaillement($ravitaillement);
            $approvisionnement->setDateExpiration($expirer);
            $approvisionnement->setStockRestant($stock);
            $approvisionnement->setPrixVente($prix_vente);

        	$em->persist($approvisionnement);

            $prixProduit = $this->getDoctrine()
                                ->getRepository('AppBundle:PrixProduit')
                                ->findBy(array('prixVente' => $prix_vente, 'agence' => $agence, 'produit' => $produit));
            if(count($prixProduit) > 0){
                $prixProduit->setPrixVente($prix_vente);
                $prixProduit->setStock($stock);
                $prixProduit->setAgence($agence);
                $prixProduit->setProduit($produit);
            }else{
                $prixProduit = new PrixProduit();
                $prixProduit->setPrixVente($prix_vente);
                $prixProduit->setStock($stock);
                $prixProduit->setAgence($agence);
                $prixProduit->setProduit($produit);
                $em->persist($prixProduit);
            }
            $approvisionnement->setPrixProduit($prixProduit);
        	$em->flush();
        }

        return new JsonResponse(array(
        	'id' => $produit->getId()
        ));
    	
    }

    public function consultationAction($categorie)
    {
        $agences  = $this->getDoctrine()
                        ->getRepository('AppBundle:Agence')
                        ->findAll();

        $categorieProduit  = $this->getDoctrine()
                        ->getRepository('AppBundle:CategorieProduit')
                        ->find($categorie);

        $permission_user = $this->get('app.permission_user');
        $user = $this->getUser();
        $permissions = $permission_user->getPermissions($user);

        $categories  = $this->getDoctrine()
                        ->getRepository('AppBundle:CategorieProduit')
                        ->findAll();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        return $this->render('ProduitBundle:Default:consultation.html.twig', array(
            'agences' => $agences,
            'userAgence' => $userAgence,
            'categorieProduit' => $categorieProduit,
            'categories' => $categories,
        ));
    }

    public function listAction(Request $request)
    {
        $agence = $request->request->get('agence');
        $recherche_par = $request->request->get('recherche_par');
        $a_rechercher = $request->request->get('a_rechercher');
        $categorie = $request->request->get('categorie');

        $produits  = $this->getDoctrine()
                        ->getRepository('AppBundle:Produit')
                        ->getList($agence,
                            $recherche_par,
                            $a_rechercher,
                            $categorie
                        );

        return new JsonResponse($produits);
    }

    public function showAction($id)
    {

        $produit = $this->getDoctrine()
                ->getRepository('AppBundle:Produit')
                ->find($id);

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $print = false;

        $pdfAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:PdfAgence')
                    ->findBy(array(
                        'agence' => $agence
                    ));
                    
        if (count($pdfAgence) > 0) {
            foreach ($pdfAgence as $key => $value) {
                if($value->getProduit()){
                    $print = true;
                }
            }
        }

        return $this->render('ProduitBundle:Default:show.html.twig',array(
            'produit' => $produit,
            'print' => $print
        ));
    }

    public function pdfAction($id)
    {
        $produit  = $this->getDoctrine()
                        ->getRepository('AppBundle:Produit')
                        ->find($id);

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $pdfAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:PdfAgence')
                    ->findBy(array(
                        'agence' => $agence
                    ));

        $modelePdf = null;
        if ($pdfAgence && $pdfAgence->getProduit()) {
           $modelePdf = $pdfAgence->getProduit();
        }      

        $template = $this->renderView('ProduitBundle:Default:pdf.html.twig', array(
            'produit' => $produit,
            'modelePdf' => $modelePdf,
        ));

        $html2pdf = $this->get('app.html2pdf');

        $html2pdf->create();

        return $html2pdf->generatePdf($template, "produit" . $produit->getId());

    }

    public function listPrixProduitAction(Request $request)
    {
        $produit_id = $request->request->get('produit_id');

        $produit  = $this->getDoctrine()
                         ->getRepository('AppBundle:Produit')
                         ->find($produit_id);

        $prixProduit = $this->getDoctrine()
                            ->getRepository('AppBundle:Approvisionnement')
                            ->findBy(array('produit' => $produit));

        return $this->render('ProduitBundle:Default:list-prix-produit.html.twig',array(
            'prixProduits' => $prixProduit
        ));
    }

    public function saveStatutProduitAction(Request $request)
    {
        $approvisionnement = $request->request->get('prixProduit');
        $status = $request->request->get('status');
        $expirer = $request->request->get('expirer');

        $approvisionnement = $this->getDoctrine()
                            ->getRepository('AppBundle:Approvisionnement')
                            ->find($approvisionnement);
        $em = $this->getDoctrine()->getManager();
        if($approvisionnement){
            $lastStatut = $approvisionnement->getStatus();
            $approvisionnement->setStatus($status);
            $em->flush();
            if($expirer){
                $expirer = explode('/', $expirer);
                $expirer = $expirer[2].'-'.$expirer[1].'-'.$expirer[0];
                $expirer = new \DateTime($expirer);
                $approvisionnement->setDateExpiration($expirer);
                $stock = $approvisionnement->getStockRestant();
                if(!$stock)
                    $stock = $approvisionnement->getQte();
                $prixProduit = $approvisionnement->getPrixProduit();
                if($status != 0){
                    if($prixProduit){
                        $prixProduit->setStock($prixProduit->getStock() - $stock);
                    }
                }
                if($lastStatut != 0){
                    if($prixProduit){
                        $prixProduit->setStock($prixProduit->getStock() + $stock);
                    }
                }
                $em->flush();
            }
        }

        return new JsonResponse(1);
    }
}
