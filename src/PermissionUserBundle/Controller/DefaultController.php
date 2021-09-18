<?php

namespace PermissionUserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\PermissionUser;

class DefaultController extends Controller
{
    public function indexAction()
    {
    	$agents = $this->getDoctrine()
                ->getRepository('AppBundle:AgentGap')
                ->findAll();
        return $this->render('PermissionUserBundle:Default:index.html.twig',array(
        	'agents' => $agents
        ));
    }

    public function listAction($user_id)
    {
    	$permissions = $this->getDoctrine()
                ->getRepository('AppBundle:PermissionUser')
                ->getList($user_id);

        return $this->render('PermissionUserBundle:Default:list.html.twig', array(
            'permissions' => $permissions,
        ));

    }

    public function saveAction(Request $request)
    {
    	$permissions = $request->request->get('permissions');
		$user_id = $request->request->get('user_id');

		$user = $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->find($user_id);

		$old = $this->getDoctrine()
                ->getRepository('AppBundle:PermissionUser')
                ->findOneBy(array(
                	'user' => $user
                ));

        $em = $this->getDoctrine()->getManager();

        if ($old) {
	        $em->remove($old);
	        $em->flush();
        }


        $pu = new PermissionUser();
        $pu->setPermission($permissions);
        $pu->setUser($user);

        $em->persist($pu);
        $em->flush();

        return new Response(200);


    }
}
