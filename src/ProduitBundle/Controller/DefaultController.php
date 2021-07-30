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
        $unite = $request->request->get('unite');
        $stock_alerte = $request->request->get('stock_alerte');
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
        	$approvisionnement = new Approvisionnement();

        	$approvisionnement->setDate($dateCreation);
        	$approvisionnement->setQte($stock);
        	$approvisionnement->setPrixAchat($prix_achat);
        	$approvisionnement->setTotal( ( $stock * $prix_achat ) );
        	$approvisionnement->setDescription(' Création du produit ' . $nom . ' le ' . $dateCreation->format('d/m/Y') . ' ('. $stock . ' ' . $unite .')' );
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

        $produits  = $this->getDoctrine()
                        ->getRepository('AppBundle:Produit')
                        ->getList($agence);

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


}
