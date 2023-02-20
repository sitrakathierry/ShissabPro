<?php

namespace RestaurantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\SliderRestaurant;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class SliderController extends Controller
{
	public function saveAction(Request $request)
    {
        $id = $request->request->get('id');
        $titre = $request->request->get('titre');
        $sous_titre = $request->request->get('sous_titre');
        $slider_img = $request->request->get('slider_img');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        if ($id) {
            $slider = $this->getDoctrine()
                ->getRepository('AppBundle:SliderRestaurant')
                ->find($id);
        } else {
            $slider = new SliderRestaurant();
        }

        $slider->setTitre($titre);
        $slider->setSousTitre($sous_titre);
        $slider->setImg($slider_img);
        $slider->setAgence($agence);

        $em = $this->getDoctrine()->getManager();
        $em->persist($slider);
        $em->flush();

        return new JsonResponse(array(
            'id' => $slider->getId()
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
                    
        $agence = $userAgence->getAgence();

        $sliders = $this->getDoctrine()
                ->getRepository('AppBundle:SliderRestaurant')
                ->list($agence->getId());

        return new JsonResponse($sliders);
        
    }

    public function showAction($id)
    {
        $slider = $this->getDoctrine()
                ->getRepository('AppBundle:SliderRestaurant')
                ->find($id);

        return $this->render('RestaurantBundle:Menus:slider-show.html.twig',array(
            'slider' => $slider,
        ));
    }
}
