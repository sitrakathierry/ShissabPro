<?php

namespace RestaurantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\CategoriePlat;

class CategorieController extends Controller
{

	public function indexAction()
    {

    	$user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        return $this->render('RestaurantBundle:Categorie:index.html.twig',array(
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
                    
        $agence_id = $userAgence->getAgence()->getId();

        $categories = $this->getDoctrine()
                ->getRepository('AppBundle:CategoriePlat')
                ->list($agence_id);

        return new JsonResponse($categories);
    }

    public function saveAction(Request $request)
    {
        $nom = $request->request->get('nom');
        $description = $request->request->get('description');
        $id = $request->request->get('id');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        if ($id) {
            $categorie = $this->getDoctrine()
                    ->getRepository('AppBundle:CategoriePlat')
                    ->find($id);
        } else {
            $categorie = new CategoriePlat();
        }

        $categorie->setNom($nom);
        $categorie->setDescription($description);
        $categorie->setAgence($agence);

        $em = $this->getDoctrine()->getManager();
        $em->persist($categorie);
        $em->flush();

        if ($categorie->getId()) {
            return new Response(200);
        }
        
    }

    public function deleteAction($id)
    {
        $categorie  = $this->getDoctrine()
                        ->getRepository('AppBundle:CategoriePlat')
                        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();

        return new JsonResponse(200);
        
    }

    public function editorAction(Request $request)
    {
        $id = $request->request->get('id');
        $categorie = $this->getDoctrine()
        	->getRepository('AppBundle:CategoriePlat')
            ->find($id);

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        return $this->render('@Restaurant/Categorie/editor.html.twig',[
            'categorie' => $categorie,
        ]);
    }

}
