<?php

namespace RestaurantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class MenusController extends Controller
{
	public function indexAction($id)
    {
        $agence = $this->getDoctrine()
                ->getRepository('AppBundle:Agence')
                ->find($id);

        $categories = $this->getDoctrine()
                ->getRepository('AppBundle:CategoriePlat')
                ->findBy(array(
                    'agence' => $agence
                ));

        $sliders = $this->getDoctrine()
                ->getRepository('AppBundle:SliderRestaurant')
                ->findBy(array(
                    'agence' => $agence
                ));

        return $this->render('RestaurantBundle:Menus:index.html.twig',array(
            'agence' => $agence,
            'categories' => $categories,
            'sliders' => $sliders,
        ));
    }

    public function listAction(Request $request)
    {
        $agence_id = $request->request->get('agence_id');
        $categorie = $request->request->get('categorie');
        $type_menu = $request->request->get('type_menu');

        $agence = $this->getDoctrine()
                ->getRepository('AppBundle:Agence')
                ->find($agence_id);

        $options = array(
            'statut' => 1,
            'agence' => $agence,
            'type' => 1
        );

        if ($categorie) {
            $categorie = $this->getDoctrine()
                ->getRepository('AppBundle:CategoriePlat')
                ->find($categorie);

            $options['categoriePlat'] = $categorie;
        }

        $plats = $this->getDoctrine()
            ->getRepository('AppBundle:Plat')
            ->findBy($options);

        $boissons = $this->getDoctrine()
            ->getRepository('AppBundle:Plat')
            ->findBy(array(
                'type' => 2,
                'agence' => $agence,
            ));

        $accompagnements = $this->getDoctrine()
            ->getRepository('AppBundle:Accompagnement')
            ->findBy(array(
                'agence' => $agence
            ));


        $template = $this->renderView('RestaurantBundle:Menus:list.html.twig',array(
            'agence' => $agence,
            'plats' => $plats,
            'boissons' => $boissons,
            'accompagnements' => $accompagnements,
            'type_menu' => $type_menu,
        ));

        return new JsonResponse(array(
            'template' => $template,
        ));
    }

    public function qrcodeAction()
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();


        return $this->render('RestaurantBundle:Menus:qrcode.html.twig',array(
            'agence' => $agence,
        ));
    }

}
