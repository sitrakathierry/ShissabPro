<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Entity\UserAgence;
use AppBundle\Entity\UserEntrepot;
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

    public function verifyAction(Request $request)
    {
        $nom = $request->request->get('nom');

        $users = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findBy(array(
                'username' => $nom
            ));

        if (!empty($users)) {
            return new Response (1);
        }

        return new Response (2);

    }

    public function saveAction(Request $request)
    {

        $u_nom = $request->request->get('u_nom');
        $u_status = $request->request->get('u_status');
        $u_email = $request->request->get('u_email');
        $u_pass = $request->request->get('u_pass');
        $u_role = $request->request->get('u_role');
        $u_agence = $request->request->get('u_agence');
        $entrepot = $request->request->get('entrepot');
        $u_responsable = $request->request->get('u_responsable');
        $image_pic = $request->request->get('image_pic');
        $u_id = $request->request->get('u_id');
        $isNew = false;

        $verif_email = $this->getDoctrine()
                        ->getRepository('AppBundle:User')
                        ->verifyEmail($u_email);

        
        // VERIFICATION DE L'EMAIL
        if(!empty($verif_email) && !$u_id)
        {
            return new Response(-2);
        }
        else
        {
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
            if (!$u_id)
                $em->persist($userAgence);
            $em->flush();


            if ($entrepot) {
                $userEntrepot = $this->getDoctrine()
                    ->getRepository('AppBundle:UserEntrepot')
                    ->findOneBy(array(
                        'user' => $user
                    ));

                if (!$userEntrepot) {
                    $userEntrepot = new userEntrepot();
                }

                $entrepot = $this->getDoctrine()
                    ->getRepository('AppBundle:Entrepot')
                    ->find($entrepot);

                $userEntrepot->setEntrepot($entrepot);
                $userEntrepot->setUser($user);
                if (!$userEntrepot)
                    $em->persist($userEntrepot);
                $em->flush();
            }


            // return ($isNew) ? $this->redirectToRoute('user_add') : $this->redirectToRoute('user_list');

            return new Response($user->getId());

        }
        
    	
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

        $userEntrepot = $this->getDoctrine()
                ->getRepository('AppBundle:UserEntrepot')
                ->findOneBy(array(
                    'user' => $user
                ));

        $agences = $this->getDoctrine()
            ->getRepository('AppBundle:Agence')
            ->findAll();

        $entrepots  = $this->getDoctrine()
                        ->getRepository('AppBundle:Entrepot')
                        ->findBy(array(
                            'agence' => $agent->getAgence()
                        ));

        $checkEntrepot = $this->checkEntrepot();

        return $this->render('UserBundle:Default:show.html.twig',array(
            'user' => $user,
        	'agent' => $agent,
            'userEntrepot' => $userEntrepot,
            'agences' => $agences,
            'entrepots' => $entrepots,
            'checkEntrepot' => $checkEntrepot
        ));
    }

    public function checkEntrepot()
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $route = 'entrepot_index';
                    
        $agence = $userAgence->getAgence();

        $menu = $this->getDoctrine()
                    ->getRepository('AppBundle:Menu')
                    ->findOneBy(array(
                        'route' => $route
                    ));

        if (!$menu) {
            return false;
        }

        $menuParAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:MenuParAgence')
                    ->findOneBy(array(
                        'menu' => $menu,
                        'agence' => $agence,
                    ));

        if (!$menuParAgence) {
            return false;
        }

        return true;
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
        ->find($id);

        $agent = $this->getDoctrine()
            ->getRepository('AppBundle:UserAgence')
            ->findOneBy(array(
                'user' => $user
            ));
        $em->remove($agent);
        $em->flush();

        $em->remove($user);
        $em->flush();

        return new Response(200);

    }

    public function activeAction($id)
    {
        $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->enableUserInAgence($id);

        return new Response(200);
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
