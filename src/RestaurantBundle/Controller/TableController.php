<?php

namespace RestaurantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\TableRestaurant;

class TableController extends Controller
{
	public function indexAction()
    {
        return $this->render('RestaurantBundle:Table:index.html.twig');
    }

    public function addAction()
    {
        return $this->render('RestaurantBundle:Table:add.html.twig');
    }

    public function saveAction(Request $request)
    {
        $id = $request->request->get('id');
        $nom = $request->request->get('nom');
        $place = $request->request->get('place');
        $statut = $request->request->get('statut');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        if ($id) {
            $table = $this->getDoctrine()
                ->getRepository('AppBundle:TableRestaurant')
                ->find($id);
        } else {
            $table = new TableRestaurant();
            $table->setDisponible($place);
        }

        $table->setNom($nom);
        $table->setPlace($place);
        $table->setStatut($statut);
        $table->setAgence($agence);

        $em = $this->getDoctrine()->getManager();
        $em->persist($table);
        $em->flush();

        return new JsonResponse(array(
            'id' => $table->getId()
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

        return $this->render('RestaurantBundle:Table:consultation.html.twig', array(
            'agences' => $agences,
            'userAgence' => $userAgence,
        ));
    }

    public function listAction(Request $request)
    {
        $agence = $request->request->get('agence');
        $recherche_par = $request->request->get('recherche_par');
        $a_rechercher = $request->request->get('a_rechercher');

        $tables  = $this->getDoctrine()
                        ->getRepository('AppBundle:TableRestaurant')
                        ->getList($agence,
                            $recherche_par,
                            $a_rechercher
                        );

        return new JsonResponse($tables);
    }

    public function showAction($id)
    {

        $table = $this->getDoctrine()
                ->getRepository('AppBundle:TableRestaurant')
                ->find($id);

        return $this->render('RestaurantBundle:Table:show.html.twig',array(
            'table' => $table,
        ));
    }

    public function statutAction($id, $statut)
    {
        $table = $this->getDoctrine()
                ->getRepository('AppBundle:TableRestaurant')
                ->find($id);

        $table->setStatut($statut);

        $em = $this->getDoctrine()->getManager();
        $em->persist($table);
        $em->flush();

        return new JsonResponse(array(
            'id' => $table->getId()
        ));
    }

    public function deleteAction($id)
    {
        $table  = $this->getDoctrine()
                        ->getRepository('AppBundle:TableRestaurant')
                        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($table);
        $em->flush();

        return new JsonResponse(200);
    }

    public function placeDisponibleAction()
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        $places = $this->getDoctrine()
                ->getRepository('AppBundle:TableRestaurant')
                ->placeDisponible($agence->getId());

        return new JsonResponse($places);
    }

    public function disponibleAction()
    {

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        $tables = $this->getDoctrine()
                ->getRepository('AppBundle:TableRestaurant')
                ->tablesDisponible($agence->getId());

        return $this->render('RestaurantBundle:Table:disponible.html.twig',array(
            'tables' => $tables,
        ));
    }
}
