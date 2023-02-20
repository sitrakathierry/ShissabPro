<?php

namespace AppBundle\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class CheckMenuService extends Controller
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function check($routes,$user,$doctrine)
    {

        $userAgence = $doctrine->getRepository('AppBundle:UserAgence')
                        ->findOneBy(array(
                            'user' => $user
                        ));
                    
        $agence = $userAgence->getAgence();

        foreach ($routes as $route) {

            $menu = $doctrine->getRepository('AppBundle:Menu')
                        ->findOneBy(array(
                            'route' => $route
                        ));

            if (!$menu) { return false; }

            $menuParAgence = $doctrine->getRepository('AppBundle:MenuParAgence')
                                ->findOneBy(array(
                                    'menu' => $menu,
                                    'agence' => $agence,
                                ));

            if (!$menuParAgence) { return false; }

            $menuParUtilisateur = $doctrine->getRepository('AppBundle:MenuUtilisateur')
                                ->findOneBy(array(
                                    'menu' => $menu,
                                    'user' => $user,
                                ));
                                
            if (!$menuParUtilisateur) { return false; }
        }

        return true;
    }

}