<?php

namespace MenuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class BaseController extends Controller
{

	public function verifyPermission(Request $request)
	{
		$route = $request->attributes->get('_route');

		$menu  = $this->getDoctrine()
                        ->getRepository('AppBundle:Menu')
                        ->findOneBy(array(
                        	'route' => $route
                        ));

        $role = $this->maxRole();
        $user = $this->getUser();

        $has  = $this->getDoctrine()
                        ->getRepository('AppBundle:Menu')
                        ->roleHasMenu($menu,$role,$user);

        if (!$has) {

            echo "Accès réfusé!";
            die();

        	throw new AccessDeniedException('Unauthorized access!');
        }

	}

	public function maxRole()
    {
    	$user = $this->getUser();

        return $user->getRoles()[0];

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
