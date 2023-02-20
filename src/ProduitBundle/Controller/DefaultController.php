<?php

namespace ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Produit;
use AppBundle\Entity\VariationProduit;
use AppBundle\Entity\Approvisionnement;
use AppBundle\Entity\Ravitaillement;
use AppBundle\Entity\PrixProduit;
use AppBundle\Entity\ProduitEntrepot;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
 
class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ProduitBundle:Default:index.html.twig');
    }

    public function addAction($categorie)
    {
        $user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $userEntrepot = $this->getDoctrine()
                    ->getRepository('AppBundle:UserEntrepot')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $agence = $userAgence->getAgence();

        $preference = $this->getDoctrine()
            ->getRepository('AppBundle:PreferenceCategorie')
            ->findOneBy(array(
                'agence' => $agence
            ));
        $categories = [];
        if ($preference) {
            $categoriesList = $preference->getCategoriesList();

            foreach ($categoriesList as $id) {
                $item = $this->getDoctrine()
                    ->getRepository('AppBundle:CategorieProduit')
                    ->find($id);
                array_push($categories, $item);
            }
        }

   
        // $categories = $this->getDoctrine()
        //     ->getRepository('AppBundle:CategorieProduit')
        //     ->findAll();

        $categorieProduit = $this->getDoctrine()
                    ->getRepository('AppBundle:CategorieProduit')
                    ->find($categorie);

        // $categories = $this->getDoctrine()
        //             ->getRepository('AppBundle:CategorieProduit')
        //             ->findAll();

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

        return $this->render('ProduitBundle:Default:add.html.twig', array(
            'userEntrepot' => $userEntrepot,
            'agence' => $agence,
            'categorieProduit' => $categorieProduit,
            'categories' => $categories,
            'entrepots' => $entrepots,
            'fournisseurs' => $fournisseurs,
        ));
    }

    public function saveAction(Request $request)
    {
        $id = $request->request->get('id');
        $code = $request->request->get('code');
        $qrcode = $request->request->get('qrcode');
        $nom = $request->request->get('nom');
        $description = $request->request->get('description');
        $produit_image = $request->request->get('produit_image');
        $categorie = $request->request->get('categorie');
        $unite = $request->request->get('unite');
        $dateCreation = new \DateTime('now');
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        $creation = true;

        /**
         * Produit
         */
        if ($id) {
            $produit = $this->getDoctrine()
                ->getRepository('AppBundle:Produit')
                ->find($id);

            $creation = false;

        } else {
            $produit = new Produit();
            $produit->setDate($dateCreation);
        }

        $produit->setCodeProduit($code);
        $produit->setQrcode($qrcode);
        $produit->setNom($nom);
        $produit->setDescription($description);
        $produit->setAgence($agence);
        $produit->setImage($produit_image);
        $produit->setUnite($unite);

        $categorie = $this->getDoctrine()
                ->getRepository('AppBundle:CategorieProduit')
                ->find($categorie);

        if ($categorie) {
            $produit->setCategorieProduit($categorie);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($produit);
        $em->flush();


        if (!!$creation) {

            $indices = $request->request->get('indice');
            $entrepots = $request->request->get('entrepot');
            $fournisseurs = $request->request->get('fournisseur');
            $prix_achats = $request->request->get('prix_achat');
            $charges = $request->request->get('charge');
            $prix_revients = $request->request->get('prix_revient');
            $marge_types = $request->request->get('marge_type');
            $marge_valeurs = $request->request->get('marge_valeur');
            $prix_ventes = $request->request->get('prix_vente');
            $stocks = $request->request->get('stock');
            $stock_alertes = $request->request->get('stock_alerte');
            $expirers = $request->request->get('expirer');
            $stock_produit = 0;

            if (!empty($indices)) {
                foreach ($indices as $key => $value) {

                    /**
                     * ProduitEntrepot
                     */
                    $indice = $indices[$key];
                    $stock = $stocks[$key];
                    $stock_alerte = $stock_alertes[$key];
                    $entrepot = $entrepots[$key];

                    $produitEntrepot = new ProduitEntrepot();

                    $produitEntrepot->setIndice($indice);
                    $produitEntrepot->setStock($stock);
                    $produitEntrepot->setStockAlerte($stock_alerte);
                    $produitEntrepot->setProduit($produit);

                    $entrepot = $this->getDoctrine()
                        ->getRepository('AppBundle:Entrepot')
                        ->find($entrepot);

                    $produitEntrepot->setEntrepot($entrepot);

                    $em->persist($produitEntrepot); 
                    $em->flush();

                    /**
                     * VariationProduit
                     */
                    $prix_vente = $prix_ventes[$key];
                    $marge_type = $marge_types[$key];
                    $marge_valeur = $marge_valeurs[$key];

                    $variation = new VariationProduit();
                    
                    $variation->setPrixVente($prix_vente);
                    $variation->setMargeType($marge_type);
                    $variation->setMargeValeur($marge_valeur);
                    $variation->setStock($stock);
                    $variation->setProduitEntrepot($produitEntrepot);
                    // $variation->setproduitId($produit) ;
                    $em->persist($variation);
                    $em->flush();
                    
                    /**
                     * Ravitaillement
                     */
                    $prix_achat = $prix_achats[$key];
                    $charge = $charges[$key];
                    if(is_numeric($charge))
                    {
                        $charge = $charges[$key];
                    }
                    else
                    {
                        $charge = 0 ;
                    }
                    $ravitaillement = new Ravitaillement();

                    $ravitaillement->setDate($dateCreation);
                    $ravitaillement->setTotal( ( $stock * ( $prix_achat + $charge ) ) );
                    $ravitaillement->setAgence( $agence );

                    $em->persist($ravitaillement);
                    $em->flush();
                    
                    /**
                     * Approvisionnement
                     */
                    $prix_revient = $prix_revients[$key];
                    $expirer = $expirers[$key];
                    $fournisseur = $fournisseurs[$key];

                    $approvisionnement = new Approvisionnement();

                    $approvisionnement->setFournisseurs( json_encode($fournisseur) );
                    $approvisionnement->setDate($dateCreation);
                    $approvisionnement->setQte($stock);
                    $approvisionnement->setPrixAchat($prix_achat);
                    $approvisionnement->setCharge($charge);
                    $approvisionnement->setPrixRevient($prix_revient);
                    $approvisionnement->setTotal( ( $stock * ( $prix_achat + $charge) ) );
                    $approvisionnement->setDescription(' Création du produit ' . $nom . ' le ' . $dateCreation->format('d/m/Y') . ' ('. $stock . ' ' . $unite .')' );
                    $approvisionnement->setRavitaillement($ravitaillement);
                    $approvisionnement->setVariationProduit($variation);
                    
                    if ($expirer) {
                        $expirer = \DateTime::createFromFormat('j/m/Y', $expirer);
                        $approvisionnement->setDateExpiration($expirer);
                    }

                    $em->persist($approvisionnement);
                    $em->flush();

                    $stock_produit += $stock;
                }
            }

            $produit->setStock($stock_produit);
            $em->persist($produit);
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

        // $categories  = $this->getDoctrine()
        //                 ->getRepository('AppBundle:CategorieProduit')
        //                 ->findAll();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $categories = [];

        $agence = $userAgence->getAgence();

        $produits = $this->getDoctrine()
                ->getRepository('AppBundle:Produit')
                ->findBy(array(
                    'agence' => $agence,
                    'is_delete' => NULL
                ));
                
        $preference = $this->getDoctrine()
                ->getRepository('AppBundle:PreferenceCategorie')
                ->findOneBy(array(
                    'agence' => $agence,
                ));

        if ($preference) {
            $categoriesList = $preference->getCategoriesList();

            foreach ($categoriesList as $id) {
                $item = $this->getDoctrine()
                    ->getRepository('AppBundle:CategorieProduit')
                    ->find($id);
                array_push($categories, $item);
            }
        }

        return $this->render('ProduitBundle:Default:consultation.html.twig', array(
            'agences' => $agences,
            'userAgence' => $userAgence,
            'categorieProduit' => $categorieProduit,
            'categories' => $categories,
            'produits' => $produits,
        ));
    }

    public function listAction(Request $request)
    {
        $agence = $request->request->get('agence');
        $recherche_par = $request->request->get('recherche_par');
        $a_rechercher = $request->request->get('a_rechercher');
        $categorie = $request->request->get('categorie');
        $produit_id = $request->request->get('produit_id');

        $produits  = $this->getDoctrine()
                        ->getRepository('AppBundle:Produit') 
                        ->getList($agence,
                            $recherche_par,
                            $a_rechercher,
                            $categorie,
                            $produit_id
                        );
        $totalStock = 0 ;
        $lstProduit = [] ;
        for ($i=0; $i < count($produits) ; $i++) { 
                $totalStock = $this->getDoctrine() 
                ->getRepository('AppBundle:VariationProduit') 
                ->getTotalVariationProduit($agence,$produits[$i]["id"]) ;
                $produits[$i]["stock"] = number_format($totalStock["stockG"],0,"."," ") ;

                if(empty($produits[$i]["stock"]))
                {
                    $produits[$i]["stock"] = 0 ;
                }
        }

        return new JsonResponse($produits);
    }

    public function consultationDeduitAction(){

        return $this->render('@Produit/Default/produit-deduit.html.twig');
    }

    public function listDeduitAction(Request $request)
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = ($userAgence->getAgence()) ? $userAgence->getAgence()->getId() : null;

        $produits  = $this->getDoctrine()
                        ->getRepository('AppBundle:ProduitDeduit')
                        ->getList($agence);

    //var_dump($userAgence->getAgence()->getId(),$produits);die;

        return $this->render('@Produit/Default/produit-deduit-liste.html.twig',array(
            'produits' => $produits
        ));
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
        
        $userEntrepot = $this->getDoctrine()
                    ->getRepository('AppBundle:UserEntrepot')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $print = false;

        $pdfs = $this->getDoctrine()
                    ->getRepository('AppBundle:PdfAgence')
                    ->findBy(array(
                        'agence' => $agence,
                        'objet' => 1,
                    ));
        if (!empty($pdfs)) {
            $print = true;
        }

        $categories = $this->getDoctrine()
                    ->getRepository('AppBundle:CategorieProduit')
                    ->findAll();

        $entrepots = $this->getDoctrine()
                ->getRepository('AppBundle:Entrepot')
                ->findBy(array(
                    'agence' => $agence
                ));
        
        $produitEntrepotList = $this->getDoctrine()
                    ->getRepository('AppBundle:ProduitEntrepot') 
                    ->findBy(array(
                        'produit' => $id
                    ));
        
        $agenceId = $agence->getId() ;
        $totalStock = $this->getDoctrine()
                ->getRepository('AppBundle:VariationProduit') 
                ->getTotalVariationProduit($agenceId,$id) ;

        return $this->render('ProduitBundle:Default:show.html.twig',array( 
            'userEntrepot' => $userEntrepot,
            'entrepots' => $entrepots,
            'agence' => $agence,
            'produit' => $produit,
            'categories' => $categories,
            'print' => $print, 
            'produitEntrepot' => $produitEntrepotList,
            'totalStock' => number_format($totalStock["stockG"],0,"."," "),
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


    public function deleteAction($id)
    {
        $produit  = $this->getDoctrine()
                        ->getRepository('AppBundle:Produit')
                        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $produit->setIsDelete(1);
        $em->flush();

        return new JsonResponse(200);
    }

    public function entrepotTplAction()
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

        $fournisseurs = $this->getDoctrine()
                ->getRepository('AppBundle:Fournisseur')
                ->findBy(array(
                    'agence' => $agence
                ));

        return $this->render('@Produit/Default/produit-entrepot.html.twig',[
            'agence' => $agence,
            'entrepots' => $entrepots,
            'fournisseurs' => $fournisseurs,
        ]);
    }


    public function affectationEntrepotAction()
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

        if (empty($entrepots)) {
            echo("Il n'y a pas d'entrepôt dans cette société, veuillez créer avant d'executer le script!");die();
        }

        $entrepot = $entrepots[0];

        $produits = $this->getDoctrine()
                ->getRepository('AppBundle:Produit')
                ->nonAffecteEntrepot($agence->getId());

        foreach ($produits as $produit) {

            $produit_entrepot = $produit['produit_entrepot'];

            $produit = $this->getDoctrine()
                ->getRepository('AppBundle:Produit')
                ->find($produit['id']);

            $stock = $produit->getStock();

            if ($produit_entrepot) {
                $produitEntrepot = $this->getDoctrine()
                    ->getRepository('AppBundle:ProduitEntrepot')
                    ->find($produit_entrepot);
            } else {
                $produitEntrepot = new ProduitEntrepot();
            }

            $produitEntrepot->setProduit($produit);
            $produitEntrepot->setEntrepot($entrepot);
            $produitEntrepot->setStock($stock);

            $em = $this->getDoctrine()->getManager();
            $em->persist($produitEntrepot);
            $em->flush();
        }

        echo "succes"; die();

    }

    public function verifyAction(Request $request)
    {
        $code = $request->request->get('code');


        $produits = $this->getDoctrine()
                ->getRepository('AppBundle:Produit')
            ->findOneBy(array(
                'codeProduit' => $code,
                'is_delete' => NULL
                ));

        if (!empty($produits)) {
            $exist = true;
        } else {
            $exist = false;
        }


         return new JsonResponse(array(
            'exist' => $exist
        ));
        
    }

    public function afficheAction(Request $request)
    {
        $idProduit = $request->request->get('idProduit');
        $entrepot = $request->request->get('entrepot');

        $produitEntrepot = $this->getDoctrine()
                                ->getRepository('AppBundle:ProduitEntrepot')
                                ->getRefProduitEntrepot($idProduit,$entrepot);

        return new JsonResponse($produitEntrepot) ;
    }

    public function miseAjourAction(){
        return new Response("Mise à jour terminé") ;
    }

    public function affichePrixAction(Request $request)
    {
        $idProduit = $request->request->get('idProduit');

        $variationPrixProduit = $this->getDoctrine()
            ->getRepository('AppBundle:VariationProduit')
            ->affichePrixProduit($idProduit);

        return new JsonResponse($variationPrixProduit);
    }

    public function affichePrixInAproAction(Request $request)
    {
        $idProduit = $request->request->get('idProduit');

        $variationPrixProduit = $this->getDoctrine()
            ->getRepository('AppBundle:VariationProduit')
            ->affichePrixProduitInAppro($idProduit);

        return new JsonResponse($variationPrixProduit);
    }

    public function afficheInfoSuppAction(Request $request)
    {
        $idVariation = $request->request->get('idVariation');

        $variationPrixProduit = $this->getDoctrine()
            ->getRepository('AppBundle:VariationProduit')
            ->getOneVariationPrixProduit($idVariation);

        return new JsonResponse($variationPrixProduit);
    }
}
