<?php

namespace RestaurantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Plat;

class PlatController extends Controller
{
	public function indexAction()
    {
        return $this->render('RestaurantBundle:Plat:index.html.twig');
    }

    public function addAction()
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        $categories = $this->getDoctrine()
                ->getRepository('AppBundle:CategoriePlat')
                ->findBy(array(
                    'agence' => $agence
                ));

        return $this->render('RestaurantBundle:Plat:add.html.twig', array(
            'agence' => $agence,
            'categories' => $categories,
        ));
    }

    public function saveAction(Request $request)
    {
        $id = $request->request->get('id');
        $nom = $request->request->get('nom');
        $description = $request->request->get('description');
        $prix = $request->request->get('prix');
        $prix_vente = $request->request->get('prix_vente');
        $plat_image = $request->request->get('plat_image');
        $categorie = $request->request->get('categorie');
        $type = $request->request->get('type');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        if ($id) {
            $plat = $this->getDoctrine()
                ->getRepository('AppBundle:Plat')
                ->find($id);
        } else {
            $plat = new Plat();
        }

        if ($categorie) {
            $categorie = $this->getDoctrine()
                ->getRepository('AppBundle:CategoriePlat')
                ->find($categorie);

            $plat->setCategoriePlat($categorie);

        }

        $plat->setNom($nom);
        $plat->setDescription($description);
        $plat->setPrix($prix);
        $plat->setPrixVente($prix_vente);
        $plat->setImg($plat_image);
        $plat->setAgence($agence);
        $plat->setType($type);

        $em = $this->getDoctrine()->getManager();
        $em->persist($plat);
        $em->flush();

        return new JsonResponse(array(
            'id' => $plat->getId()
        ));

    }

    public function consultationAction()
    {
        $agences  = $this->getDoctrine()
                        ->getRepository('AppBundle:Agence')
                        ->findAll();

        $user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        return $this->render('RestaurantBundle:Plat:consultation.html.twig', array(
            'agences' => $agences,
            'userAgence' => $userAgence,
        ));
    }

    public function listAction(Request $request)
    {
        $agence = $request->request->get('agence');
        $recherche_par = $request->request->get('recherche_par');
        $a_rechercher = $request->request->get('a_rechercher');

        $plats  = $this->getDoctrine()
                        ->getRepository('AppBundle:Plat')
                        ->getList($agence,
                            $recherche_par,
                            $a_rechercher
                        );

        return new JsonResponse($plats);
    }

    public function showAction($id)
    {

        $plat = $this->getDoctrine()
                ->getRepository('AppBundle:Plat')
                ->find($id);

        $agence = $plat->getAgence();

        $categories = $this->getDoctrine()
                ->getRepository('AppBundle:CategoriePlat')
                ->findBy(array(
                    'agence' => $agence
                ));

        return $this->render('RestaurantBundle:Plat:show.html.twig',array(
            'agence' => $agence,
            'plat' => $plat,
            'categories' => $categories,
        ));
    }

    public function statutAction($id, $statut)
    {
        $plat = $this->getDoctrine()
                ->getRepository('AppBundle:Plat')
                ->find($id);

        $plat->setStatut($statut);

        $em = $this->getDoctrine()->getManager();
        $em->persist($plat);
        $em->flush();

        return new JsonResponse(array(
            'id' => $plat->getId()
        ));
    }

    public function deleteAction($id)
    {
        $plat  = $this->getDoctrine()
                        ->getRepository('AppBundle:Plat')
                        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($plat);
        $em->flush();

        return new JsonResponse(200);
    }
}
