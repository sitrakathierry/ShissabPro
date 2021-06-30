<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Entity\UserAgence;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('UserBundle:Default:index.html.twig');
    }

    public function addAction()
    {
        $agences = $this->getDoctrine()
            ->getRepository('AppBundle:Agence')
            ->findAll();

        return $this->render('UserBundle:Default:add.html.twig',array(
            'agences' => $agences,
        ));
    }

    public function saveAction(Request $request)
    {
        $u_nom = $request->request->get('u_nom');
        $u_status = $request->request->get('u_status');
        $u_email = $request->request->get('u_email');
        $u_pass = $request->request->get('u_pass');
        $u_role = $request->request->get('u_role');
        $u_agence = $request->request->get('u_agence');
        $u_responsable = $request->request->get('u_responsable');
        $image_pic = $request->request->get('image_pic');
        $u_id = $request->request->get('u_id');
        $isNew = false;

        if ($u_id) {
            $user = $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->find($u_id);

            $userAgence = $this->getDoctrine()
                ->getRepository('AppBundle:UserAgence')
                ->findOneBy(array(
                    'user' => $user
                ));
        } else {
            $isNew = true;
            $user = new User();
            $userAgence = new UserAgence();
        }

        if ($u_role != 'ROLE_ADMIN') {
            $agence = $this->getDoctrine()
                    ->getRepository('AppBundle:Agence')
                    ->find($u_agence);

            $userAgences = $this->getDoctrine()
                                ->getRepository('AppBundle:UserAgence')
                                ->countUserByAgence($agence);

            if(count($userAgences) >= $agence->getCapacite() && $u_status == "on" && !!$isNew){
                return new Response(-1);
            }
        } else {
            $agence = $this->getDoctrine()
                    ->getRepository('AppBundle:Agence')
                    ->find(1);
        }

        $user->setUserName($u_nom);
        $user->setUserNameCanonical($u_nom);
        $user->setEmail($u_email);
        $user->setEmailCanonical($u_email);
        
        if($image_pic)
            $user->setLogo($image_pic);

        if ($u_pass || $u_pass != "" || isset($u_pass)) {
            $user->setPlainPassword($u_pass);
        }

        if ($u_status == "on") {
            $user->setEnabled(1);
        } else{
            $user->setEnabled(0);
        }


        $roles = array();
        array_push($roles, $u_role);
        $user->setRoles($roles);
        $userManager = $this->get('fos_user.user_manager');
		$userManager->updateUser($user);

        /**
         * user agence
         */

        $userAgence->setAgence($agence);
        $userAgence->setUser($user);
        $userAgence->setResponsable($u_responsable);

        $em = $this->getDoctrine()->getManager();
        $em->persist($userAgence);
        $em->flush();
        
        
        // return ($isNew) ? $this->redirectToRoute('user_add') : $this->redirectToRoute('user_list');

        return new Response( $user->getId() );

    	
    }

    public function listUserAgenceAction($agence_id)
    {

        $agence = $this->getDoctrine()
                ->getRepository('AppBundle:Agence')
                ->find($agence_id);

        $agents = $this->getDoctrine()
                ->getRepository('AppBundle:UserAgence')
                ->findBy(array(
                    'agence' => $agence
                ));

        return $this->render('UserBundle:Default:list-user-agence.html.twig',array(
            'agents' => $agents,
            'agence' => $agence,
        ));
    }

    public function showAction($id)
    {
    	$user = $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->find($id);

        $agent = $this->getDoctrine()
                ->getRepository('AppBundle:UserAgence')
                ->findOneBy(array(
                    'user' => $user
                ));

        $agences = $this->getDoctrine()
            ->getRepository('AppBundle:Agence')
            ->findAll();

        return $this->render('UserBundle:Default:show.html.twig',array(
            'user' => $user,
        	'agent' => $agent,
            'agences' => $agences,
        ));
    }

    public function deleteAction($id)
    {
        $user = $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->find($id);

        if($user){
            $user->setEnabled(0);
        }
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('user_list');

    }

    public function logsAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->render('UserBundle:Default:log.html.twig');
        }else{
            $logs = $this->getDoctrine()
            ->getRepository('AppBundle:Logs')
            ->getLogs();
            return new JsonResponse($logs);
        }
    }

    public function listUserAction()
    {
        $agents = $this->getDoctrine()
                ->getRepository('AppBundle:UserAgence')
                ->findAll();

        return $this->render('UserBundle:Default:list-user.html.twig',array(
            'agents' => $agents,
        ));
    }

}
