<?php

namespace ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class VariationProduitController extends Controller
{
    public function listAction(Request $request)
    {
    	$produit_id = $request->request->get('produit_id');

        $produit  = $this->getDoctrine()
                         ->getRepository('AppBundle:Produit')
                         ->find($produit_id);

        $variations = $this->getDoctrine()
                            ->getRepository('AppBundle:VariationProduit')
                            ->findBy(array(
                            	'produit' => $produit
                            ));

        return $this->render('ProduitBundle:VariationProduit:list.html.twig',array(
            'produit' => $produit,
            'variations' => $variations,
        ));
    }
}
