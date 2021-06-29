<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Menu;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\DependencyInjection\Container;

class MenuAllowed
{
    private $entity_manager;
    private $token_storage;
    private $authorization_checker;
    private $container;

    public function __construct(TokenStorage $storage, AuthorizationChecker $checker, EntityManager $em,Container $container)
    {
        $this->token_storage = $storage;
        $this->authorization_checker = $checker;
        $this->entity_manager = $em;
        $this->container = $container;
    }

    /**
     * Tester si l'utilisateur a un accès au menu concerné
     * pour chaque requête
     *
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {

    //     $token = $this->token_storage
    //         ->getToken();
    //     if ($token) {
    //         /** @var User $utilisateur */
    //         $utilisateur = $token->getUser();
    //         if ($utilisateur instanceof User) {
    //             $request = $event->getRequest();

    //             $route = $request->attributes->get('_route');

				// $menu  = $this->entity_manager
		  //                       ->getRepository('AppBundle:Menu')
		  //                       ->findOneBy(array(
		  //                       	'route' => $route
		  //                       ));
		  //       if ($menu || $menu != NULL) {
			 //        $role = $this->maxRole($utilisateur);

			 //        $has  = $this->entity_manager
			 //                        ->getRepository('AppBundle:Menu')
			 //                        ->roleHasMenu($menu,$role,$utilisateur);
			 //        if (!$has) {
	   //                  throw new AccessDeniedHttpException("Vous n'avez pas accès à cette page/resource.");
			 //        }
		  //       }

    //         }
    //     }
        return true;
    }

    public function maxRole($user)
    {

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