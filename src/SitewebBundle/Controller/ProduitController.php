<?php

namespace SitewebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\SitewebProduit;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProduitController extends Controller
{
	public function indexAction($id)
    {
        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id);

        return $this->render('SitewebBundle:Produit:index.html.twig',array(
            'siteweb' => $siteweb
        ));
    }

    public function saveAction(Request $request)
    {
        $id = $request->request->get('id');
        $id_siteweb = $request->request->get('id_siteweb');
        $nom = $request->request->get('nom');
        $description = $request->request->get('description');
        $produit_img = $request->request->get('produit_img');
        $produit_icon = $request->request->get('produit_icon');

        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id_siteweb);

        if ($id) {
            $produit = $this->getDoctrine()
                ->getRepository('AppBundle:SitewebProduit')
                ->find($id);
        } else {
            $produit = new SitewebProduit();
        }

        $produit->setNom($nom);
        $produit->setDescription($description);
        $produit->setImg($produit_img);
        $produit->setIcon($produit_icon);
        $produit->setSiteweb($siteweb);

        $em = $this->getDoctrine()->getManager();
        $em->persist($produit);
        $em->flush();

        return new JsonResponse(array(
            'id' => $produit->getId()
        ));
        
    }

    public function listAction(Request $request)
    {
        $id_siteweb = $request->request->get('id_siteweb');

        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id_siteweb);

        $cle = $siteweb->getCle();

        $produits = $this->getDoctrine()
                ->getRepository('AppBundle:SitewebProduit')
                ->list($cle);

        return new JsonResponse($produits);

        
    }

    public function showAction($id)
    {
        $produit = $this->getDoctrine()
                ->getRepository('AppBundle:SitewebProduit')
                ->find($id);

        $siteweb = $produit->getSiteweb();

        return $this->render('SitewebBundle:Produit:show.html.twig',array(
            'produit' => $produit,
            'siteweb' => $siteweb,
        ));
    }

    public function deleteAction($id)
    {
        $produit  = $this->getDoctrine()
                        ->getRepository('AppBundle:SitewebProduit')
                        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($produit);
        $em->flush();

        return new JsonResponse(200);
    }
}
