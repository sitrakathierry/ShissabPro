<?php

namespace MenuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
    	$user = $this->getUser();

        $role = $this->maxRole();



        $menus  = $this->getDoctrine()
                        ->getRepository('AppBundle:Menu')
                        ->byRole($role, $user); 

        /*$menus = $this->getDoctrine()
                     ->getRepository('AppBundle:Menu')
                     ->getMenu($this->getUser());*/

        return $this->render('MenuBundle:Default:menu-gauche.html.twig',array(
        	'user' => $user,
        	'menus' => $menus
        ));
    }

    public function maxRole()
    {
    	$user = $this->getUser();

        if ($user) {
            return $user->getRoles()[0];
        }

        return 'IS_AUTHENTICATED_ANONYMOUSLY';


        // if ($user) {
        // 	$roles = $this->container->getParameter('security.role_hierarchy.roles');

        // 	foreach ($roles as $role) {
        // 		if ($user->hasRole($role[0])) {
        // 			return $role[0];
        // 		}
        // 	}
        // 	return 'ROLE_USER';
        // }

        // return 'IS_AUTHENTICATED_ANONYMOUSLY';
    }

    public function getMenuListAction() 
    {
        $menu_complet = $this->getDoctrine()
                             ->getRepository('AppBundle:Menu')
                             ->getMenu($this->getUser());

        $menus_id = [];

        $role = $this->maxRole();

        if ($role == 'ROLE_SUPER_ADMIN' || $role == 'ROLE_ADMIN') {
            $menus = $this->getDoctrine()
                          ->getRepository('AppBundle:Menu')
                          ->findAll();
            foreach ($menus as $menu) {
                $menus_id[] = $menu->getId();
            }
        }else{
            $menus = $this->getDoctrine()
                          ->getRepository('AppBundle:MenuUtilisateur')
                          ->getMenuUtilisateur($this->getUser());
            foreach ( $menus as $menu ) {
                $menus_id[] = $menu->getMenu()->getId();
            }
        }
        return $this->render('MenuBundle:Default:liste-menu.html.twig', array(
            'liste_menu' => $menu_complet,
            'menus_id' => $menus_id
        ));
    }
}
