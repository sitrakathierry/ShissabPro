<?php

namespace AgenceBundle\Controller;
use AppBundle\Entity\Agence;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\User;
use AppBundle\Entity\UserAgence;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AgenceBundle:Default:index.html.twig');
    }

    public function addAction()
    {
        $user = $this->getUser();
        $role = null;
        if ($user) {
            $role = $user->getRoles()[0];
        }else{
            return 'IS_AUTHENTICATED_ANONYMOUSLY';
        }        
        return $this->render('AgenceBundle:Default:add.html.twig', ['role' => $role]);
    }

    public function saveAction(Request $request)
    {
        $nom = $request->request->get('nom');
        $region = $request->request->get('region');
        $code = $request->request->get('code');
        $id = $request->request->get('id');
        $capacite = $request->request->get('capacite');
        $adresse = $request->request->get('adresse');
        $tel = $request->request->get('tel');
        $logo_agence = $request->request->get('logo_agence');

        if ($id) {
            $agence = $this->getDoctrine()
                ->getRepository('AppBundle:Agence')
                ->find($id);
        } else{
            $agence = new Agence();
        }

        $agence->setNom($nom);
        $agence->setRegion($region);

        if ($adresse) {
            $agence->setAdresse($adresse);
        }

        if ($tel) {
            $agence->setTel($tel);
        }

        if ($logo_agence) {
            $agence->setImg($logo_agence);
        }

        
        if($capacite) $agence->setCapacite($capacite);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($agence);
        $em->flush();

        if (!$id) {
            $nom_responsable = $request->request->get('nom_responsable');
            $email_responsable = $request->request->get('email_responsable');
            $mdp_responsable = $request->request->get('mdp_responsable');
            $responsabilite = $request->request->get('responsabilite');
            
            $user = new User();
            $userAgence = new UserAgence();
            $roles = [];
            array_push($roles, "ROLE_RESPONSABLE");

            $user->setUserName($nom_responsable);
            $user->setUserNameCanonical($nom_responsable);
            $user->setEmail($email_responsable);
            $user->setEmailCanonical($email_responsable);
            $user->setPlainPassword($mdp_responsable);
            $user->setEnabled(1);

            $user->setRoles($roles);
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($user);

            $userAgence->setAgence($agence);
            $userAgence->setUser($user);
            $userAgence->setResponsable($responsabilite);

            $em->persist($userAgence);
            $em->flush();
        }


        return $this->redirectToRoute('agence_show',array(
            'id'  => $agence->getId()
        ));

    }

    public function userListAction()
    {
        $user = $this->getUser();

        $userAgence  = $this->getDoctrine()
                        ->getRepository('AppBundle:UserAgence')
                        ->findOneBy(array(
                            'user' => $user
                        ));

        $agence = $userAgence->getAgence();

        return $this->redirectToRoute('user_agence_list',array(
            'agence_id'  => $agence->getId()
        ));
    }

    public function detailsAction()
    {
        $user = $this->getUser();

        $userAgence  = $this->getDoctrine()
                        ->getRepository('AppBundle:UserAgence')
                        ->findOneBy(array(
                            'user' => $user
                        ));

        $agence = $userAgence->getAgence();

        return $this->redirectToRoute('agence_show',array(
            'id'  => $agence->getId()
        ));
        
    }

    public function showAction($id)
    {
        $user = $this->getUser();
        $role = null;
        if ($user) {
            $role = $user->getRoles()[0];
        }else{
            return 'IS_AUTHENTICATED_ANONYMOUSLY';
        }

        $agence  = $this->getDoctrine()
                        ->getRepository('AppBundle:Agence')
                        ->find($id);

        return $this->render('AgenceBundle:Default:show.html.twig', array(
            'agence' => $agence,
            'role' => $role
        ));
    }

    public function listAction()
    {
        return $this->render('AgenceBundle:Default:list.html.twig');
    }

    public function getListAction(Request $request)
    {

        $agences = $this->getDoctrine()
                ->getRepository('AppBundle:Agence')
                ->getList();

        return new JsonResponse($agences);
    }

    public function editorAction(Request $request)
    {        
        $user = $this->getUser();
        $role = null;
        if ($user) {
            $role = $user->getRoles()[0];
        }else{
            return 'IS_AUTHENTICATED_ANONYMOUSLY';
        }
        $id = $request->request->get('id');

        $agence = $this->getDoctrine()->getRepository('AppBundle:Agence')
            ->find($id);

        return $this->render('AgenceBundle:Default:editor.html.twig',array(
            'agence' => $agence,
            'role' => $role
        ));
    }

    public function deleteAction($id)
    {
        $agence  = $this->getDoctrine()
                        ->getRepository('AppBundle:Agence')
                        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($agence);
        $em->flush();

        return new JsonResponse(200);
    }

    public function addUserAction()
    {
        $user = $this->getUser();

        $userAgence  = $this->getDoctrine()
                        ->getRepository('AppBundle:UserAgence')
                        ->findOneBy(array(
                            'user' => $user
                        ));

        $agence = $userAgence->getAgence();

        $entrepots  = $this->getDoctrine()
                        ->getRepository('AppBundle:Entrepot')
                        ->findBy(array(
                            'agence' => $agence
                        ));

        $checkEntrepot = $this->checkEntrepot();

        return $this->render('AgenceBundle:Default:add-user.html.twig',array(
            'agence' => $agence,
            'entrepots' => $entrepots,
            'checkEntrepot' => $checkEntrepot,
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
}
