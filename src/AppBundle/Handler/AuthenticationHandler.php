<?php

namespace AppBundle\Handler;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use AppBundle\Entity\Logs;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\Container;

class AuthenticationHandler  implements AuthenticationSuccessHandlerInterface, LogoutSuccessHandlerInterface
{

	private $entity_manager;
	private $token_storage;
	private $container;

	public function __construct(TokenStorage $storage, EntityManager $em, Container $container)
    {
    	$this->token_storage = $storage;
        $this->entity_manager = $em;
        $this->container = $container;
    }

    function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {

    	$token = $this->token_storage
            ->getToken();

        if ($token) {
        	$user = $token->getUser();
        	$dateCreation = new \DateTime('now');
			$logs = new Logs();

			$logs->setDateModification($dateCreation);
			$logs->setMotif('Connexion Utilisateur');
			$logs->setUser($user);

	        $this->entity_manager->persist($logs);
	        $this->entity_manager->flush();
        }

        return new RedirectResponse($this->container->get('router')->generate('dashboard_homepage'));
    }

    public function onLogoutSuccess(Request $request) 
    {

    	$token = $this->token_storage
            ->getToken();

         if ($token) {
        	$user = $token->getUser();

        	$dateCreation = new \DateTime('now');
			$logs = new Logs();

			$logs->setDateModification($dateCreation);
			$logs->setMotif('Deconnexion Utilisateur');
			$logs->setUser($user);

	        $this->entity_manager->persist($logs);
	        $this->entity_manager->flush();
        }
        
        // $referer = $request->headers->get('referer');
        // return new RedirectResponse($referer);

        return new RedirectResponse($this->container->get('router')->generate('dashboard_homepage'));
    }
}