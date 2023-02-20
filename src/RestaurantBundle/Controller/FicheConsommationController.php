<?php

namespace RestaurantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FicheConsommationController extends Controller
{
    public function indexAction()
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

        return $this->render('RestaurantBundle:FicheConsommation:index.html.twig', array(
            'agences' => $agences,
            'userAgence' => $userAgence,
        ));
    }
}
