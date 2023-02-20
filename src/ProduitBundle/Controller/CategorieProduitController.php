<?php

namespace ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\CategorieProduit;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategorieProduitController extends Controller
{
	public function indexAction()
    {
        return $this->render('ProduitBundle:CategorieProduit:index.html.twig');
    }

    public function addAction()
    {
        return $this->render('ProduitBundle:CategorieProduit:add.html.twig');
    }

    public function saveAction(Request $request)
    {
        $id = $request->request->get('id');
        $nom = $request->request->get('nom');
        $categorie_image = $request->request->get('categorie_image');

        if ($id) {
            $categorie = $this->getDoctrine()
                ->getRepository('AppBundle:CategorieProduit')
                ->find($id);

        } else {
            $categorie = new CategorieProduit();

            $exist = $this->getDoctrine()
                ->getRepository('AppBundle:CategorieProduit')
                ->findOneBy(array(
                    'nom' => $nom
                ));

            if ($exist) {
                return new JsonResponse(array(
                    'exist' => true
                ));
            }

        }

        $categorie->setNom($nom);
        $categorie->setImage($categorie_image);

        $em = $this->getDoctrine()->getManager();
        $em->persist($categorie);
        $em->flush();

        return new JsonResponse(array(
            'id' => $categorie->getId()
        ));
        
    }

    public function consultationAction()
    {
        return $this->render('ProduitBundle:CategorieProduit:consultation.html.twig', array(
            // 'userAgence' => $userAgence,
        ));
    }

    public function listAction(Request $request)
    {
        $recherche_par = $request->request->get('recherche_par');
        $a_rechercher = $request->request->get('a_rechercher');

        $categories  = $this->getDoctrine()
                        ->getRepository('AppBundle:CategorieProduit')
                        ->getList(
                            $recherche_par,
                            $a_rechercher
                        );

        return new JsonResponse($categories);
    }

    public function showAction($id)
    {

        $categorie = $this->getDoctrine()
                ->getRepository('AppBundle:CategorieProduit')
                ->find($id);

        return $this->render('ProduitBundle:CategorieProduit:show.html.twig',array(
            'categorie' => $categorie,
        ));
    }
}
