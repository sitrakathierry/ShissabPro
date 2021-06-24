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
}
