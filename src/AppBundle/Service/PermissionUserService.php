<?php

namespace AppBundle\Service;
use AppBundle\Entity\Logs;
use Doctrine\ORM\EntityManagerInterface;

class PermissionUserService
{
	private $em;

	public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

	public function getPermissions($user)
	{
		if (in_array('ROLE_ADMIN', $user->getRoles())) {
			$super = array(
	        	'client' => (Object)array(
	        		'create' => true,
	        		'edit' => true,
	        		'delete' => true
	        	),
	        	'facture' => (Object)array(
	        		'create' => true,
	        		'edit' => true,
	        		'delete' => true
	        	),
	                'cotation' => (Object)array(
	                        'create' => true,
	                        'edit' => true,
	                        'delete' => true
	                ),
	                'assurance_auto' => (Object)array(
	                        'create' => true,
	                        'edit' => true,
	                        'delete' => true
	                ),
	                'assurance_maladie' => (Object)array(
	                        'create' => true,
	                        'edit' => true,
	                        'delete' => true
	                ),
	                'sinistre' => (Object)array(
	                        'create' => true,
	                        'edit' => true,
	                        'delete' => true
	                ),
	                'caisse' => (Object)array(
	                        'create' => true,
	                        'edit' => true,
	                        'delete' => true
	                ),
	                'comptabilite' => (Object)array(
	                        'create' => true,
	                        'edit' => true,
	                        'delete' => true
	                ),
	                'iard' => (Object)array(
	                        'create' => true,
	                        'edit' => true,
	                        'delete' => true
	                ),
	        );


	        return (Object)$super;
		}

		$permissions = $this->em
				->getRepository('AppBundle:PermissionUser')
                ->getList($user->getId());

        return $permissions;
		
	}
}