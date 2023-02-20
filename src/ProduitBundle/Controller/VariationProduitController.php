<?php

namespace ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\VariationProduit;
use AppBundle\Entity\ProduitEntrepot;
use AppBundle\Entity\Approvisionnement;
use AppBundle\Entity\Ravitaillement;
use AppBundle\Entity\ProduitDeduit;

class VariationProduitController extends Controller
{

    public function saveAction(Request $request)
    {
        $id_produit = $request->request->get('id_produit');
        $variation_entrepot = $request->request->get('variation_entrepot');
        $variation_indice = $request->request->get('variation_indice');
        $variation_prix_vente = $request->request->get('variation_prix_vente');
        $variation_stock = $request->request->get('variation_stock');
        $variation_operation = $request->request->get('variation_operation');
        $id_produit_entrepot = $request->request->get('id_produit_entrepot');

        $em = $this->getDoctrine()->getManager();

        $produit = $this->getDoctrine()
                ->getRepository('AppBundle:Produit')
                ->find($id_produit);

        $entrepot = $this->getDoctrine()
                ->getRepository('AppBundle:Entrepot')
                ->find($variation_entrepot);

        if ($id_produit_entrepot) {
            $produitEntrepot = $this->getDoctrine()
                ->getRepository('AppBundle:ProduitEntrepot')
                ->find($id_produit_entrepot);
        } else {
            $produitEntrepot = $this->getDoctrine()
                    ->getRepository('AppBundle:ProduitEntrepot')
                    ->findOneBy(array(
                        'produit' => $produit,
                        'entrepot' => $entrepot,
                    ));
        }
        
        if (!$produitEntrepot) {
            $produitEntrepot = new ProduitEntrepot();
        }

        // var_dump( $variation_operation );die();

        // var_dump( $produit->getStock() + $variation_stock );die();

        $produitEntrepot->setIndice($variation_indice);
        $produitEntrepot->setStock( $produitEntrepot->getStock() + $variation_stock );
        $produitEntrepot->setProduit($produit);
        $produitEntrepot->setEntrepot($entrepot);

        $em->persist($produitEntrepot);
        $em->flush();

        $variation = new VariationProduit();
        
        $variation->setPrixVente($variation_prix_vente);
        $variation->setStock($variation_stock);
        $variation->setProduitEntrepot($produitEntrepot);

        $em->persist($variation);
        $em->flush();

        if ($variation_operation == 1) {
            $produit->setStock( $produit->getStock() + $variation_stock );

            $em->persist($produit);
            $em->flush();
        }

        return new JsonResponse(array(
            'id' => $variation->getId()
        ));
        
    }

    public function listAction(Request $request)
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

    	$produit_id = $request->request->get('produit_id');
        $id_entrepot = $request->request->get('id_entrepot');

        $produit  = $this->getDoctrine()
                         ->getRepository('AppBundle:Produit')
                         ->find($produit_id);

        $options = array(
            'produit' => $produit
        );

        if ($id_entrepot) {
            $options['entrepot'] = $id_entrepot;
        }

        $produitEntrepotList = $this->getDoctrine()
                            ->getRepository('AppBundle:ProduitEntrepot')
                            ->findBy($options);
 
        foreach ($produitEntrepotList as $produitEntrepot) {
            $variations = $this->getDoctrine()
                            ->getRepository('AppBundle:VariationProduit')
                            ->findBy(array(
                                'produitEntrepot' => $produitEntrepot
                            ));
            $produitEntrepot->setVariationProduitList($variations);
        }

        $entrepots = $this->getDoctrine()
                ->getRepository('AppBundle:Entrepot')
                ->findBy(array(
                    'agence' => $agence
                ));

        $devise = $this->getDevise();

        // $variations = $this->getDoctrine()
        //                     ->getRepository('AppBundle:VariationProduit')
        //                     ->findBy(array(
        //                     	'produit' => $produit
        //                     ));

        return $this->render('ProduitBundle:VariationProduit:list.html.twig',array(
            'devise' => $devise,
            'agence' => $agence,
            'userEntrepot' => $userEntrepot,
            'entrepots' => $entrepots,
            'produit' => $produit,
            'produitEntrepotList' => $produitEntrepotList,
        ));
    }
 
    public function editorAction(Request $request)
    {
        $id = $request->request->get('id');
        $variation = $this->getDoctrine()->getRepository('AppBundle:VariationProduit')
            ->find($id);

        $produitEntrepot = $variation->getProduitEntrepot();

        $devise = $this->getDevise(); 

        return $this->render('@Produit/VariationProduit/editor.html.twig',[
            'devise' => $devise,
            'variation' => $variation,
            'produitEntrepot' => $produitEntrepot,
        ]);
    }

    public function updateAction(Request $request)
    {
        $prix_vente = $request->request->get('prix_vente');
        $id_variation = $request->request->get('id_variation');
        $is_deduct = $request->request->get('is_deduct');
        $stock = $request->request->get('stock_deduit');
        $type  = $request->request->get('type');
        $cause = $request->request->get('cause');

        $variation = $this->getDoctrine()->getRepository('AppBundle:VariationProduit')
            ->find($id_variation);
        $entrepot = ($variation->getProduitEntrepot()) ? $variation->getProduitEntrepot() : null;
        $produit  = ($entrepot) ? $variation->getProduitEntrepot()->getProduit() : null;
    //var_dump($variation->getStock(),$variation->getProduitEntrepot()->getStock(),$variation->getProduitEntrepot()->getProduit()->getStock());die;

        $stock_variation = $variation->getStock();
        $stock_entrepot  = ($variation->getProduitEntrepot()) ? $variation->getProduitEntrepot()->getStock() : null;
        $stock_produit   = ($variation->getProduitEntrepot()->getProduit()) ? $variation->getProduitEntrepot()->getProduit()->getStock() : null;

        $stock_deduit_v = ($is_deduct) ? ($stock_variation - $stock) : $stock_variation; 
        $stock_deduit_e = ($is_deduct) ? ($stock_entrepot - $stock) : $stock_entrepot; 
        $stock_deduit_p = ($is_deduct) ? ($stock_produit - $stock) : $stock_produit; 

        $variation->setStock($stock_deduit_v);
        $variation->setPrixVente($prix_vente);

        $em = $this->getDoctrine()->getManager();

        if($entrepot){
            $entrepot->setStock($stock_deduit_e);
            $em->persist($entrepot);
            $em->flush();
        }
        if($produit){
            $produit->setStock($stock_deduit_p);
            $em->persist($produit);
            $em->flush();
        }

        $em->persist($variation);
        $em->flush();

        if($type){
            if($type==2){
                $produit_deduit = new ProduitDeduit();
                $produit_deduit->setCause($cause);
                $produit_deduit->setStock($stock);
                $produit_deduit->setProduit($produit);
                $em->persist($produit_deduit);
                $em->flush();
            }
        }

        return new JsonResponse(array(
            'id' => $variation->getId()
        ));

    }

    public function deleteAction($id)
    {
        $variation  = $this->getDoctrine() 
                        ->getRepository('AppBundle:VariationProduit')
                        ->find($id);

        $variationProduit  = $this->getDoctrine() 
        ->getRepository('AppBundle:VariationProduit')
        ->getInfoVariation($id);


        $em = $this->getDoctrine()->getManager();
        $variation->setIsDelete(1);

        $produitEntrepotList = $this->getDoctrine()
                            ->getRepository('AppBundle:ProduitEntrepot')
                            ->find($variationProduit['produit_entrepot']); 
        $infoEntrepotList = $this->getDoctrine()
                            ->getRepository('AppBundle:ProduitEntrepot')
                            ->getProduitEntrepot($variationProduit['produit_entrepot']); 

        $stockProduit = intval($infoEntrepotList['stock']) - intval($variationProduit['stock']) ;
        $produitEntrepotList->setStock($stockProduit) ;
        $em->flush();

        return new JsonResponse(200);
        
    }

    public function afficheAction(Request $request)
    {
        $idProduitEntrepot = $request->request->get('idProduitEntrepot');

        $variationPrix  = $this->getDoctrine() 
                        ->getRepository('AppBundle:VariationProduit')
                        ->getVariationPrix($idProduitEntrepot);

        return new JsonResponse($variationPrix) ;
    }

    public function afficheVarPrixAction()
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $agence = $userAgence->getAgence();

        $variations = $this->getDoctrine()
                ->getRepository('AppBundle:VariationProduit')
                ->list($agence->getId());

        return new JsonResponse($variations) ;
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
