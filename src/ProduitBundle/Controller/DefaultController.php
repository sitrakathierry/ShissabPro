<?php

namespace ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Produit;
use AppBundle\Entity\Approvisionnement;
use AppBundle\Entity\Ravitaillement;
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
        	$approvisionnement = new Approvisionnement();

        	$approvisionnement->setDate($dateCreation);
        	$approvisionnement->setQte($stock);
        	$approvisionnement->setPrixAchat($prix_achat);
        	$approvisionnement->setTotal( ( $stock * $prix_achat ) );
        	$approvisionnement->setDescription(' Création du produit ' . $nom . ' le ' . $dateCreation->format('d/m/Y') . ' ('. $stock .')' );
        	$approvisionnement->setProduit($produit);
            $approvisionnement->setRavitaillement($ravitaillement);

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
        $recherche_par = $request->request->get('recherche_par');
        $a_rechercher = $request->request->get('a_rechercher');

        $produits  = $this->getDoctrine()
                        ->getRepository('AppBundle:Produit')
                        ->getList($agence,
                            $recherche_par,
                            $a_rechercher
                        );

        return new JsonResponse($produits);
    }

    public function showAction($id)
    {

        $produit = $this->getDoctrine()
                ->getRepository('AppBundle:Produit')
                ->find($id);

        $approvisionnements = $this->getDoctrine()
                ->getRepository('AppBundle:Approvisionnement')
                ->findBy(array(
                    'produit' => $produit
                ));

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
            'approvisionnements' => $approvisionnements,
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
        foreach ($pdfAgence as $key => $value) {
            if($value->getProduit()){
                $modelePdf = $value->getProduit();
            }
        }       

        $template = $this->renderView('ProduitBundle:Default:pdf.html.twig', array(
            'produit' => $produit,
            'modelePdf' => $modelePdf,
        ));

        $html2pdf = $this->get('app.html2pdf');

        $html2pdf->create();

        return $html2pdf->generatePdf($template, "produit" . $produit->getId());

    }
}
