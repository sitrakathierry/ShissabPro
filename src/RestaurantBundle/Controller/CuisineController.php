<?php

namespace RestaurantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CuisineController extends Controller
{

    public function indexAction()
    {
        return $this->render('RestaurantBundle:Cuisine:index.html.twig');
    }

	public function platAction()
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

        return $this->render('RestaurantBundle:Cuisine:plat.html.twig',array(
        	'agences' => $agences,
            'userAgence' => $userAgence,
        ));
    }

    public function boissonAction()
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

        return $this->render('RestaurantBundle:Cuisine:boisson.html.twig',array(
            'agences' => $agences,
            'userAgence' => $userAgence,
        ));
    }
}
