<?php

namespace AgenceBundle\Controller;
use AppBundle\Entity\Agence;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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

        if ($id) {
            $agence = $this->getDoctrine()
                ->getRepository('AppBundle:Agence')
                ->find($id);
        } else{
            $agence = new Agence();
        }

        $agence->setNom($nom);
        $agence->setRegion($region);
        if($code)
            $agence->setCode($code);
        if($capacite)
            $agence->setCapacite($capacite);

        $em = $this->getDoctrine()->getManager();
        $em->persist($agence);
        $em->flush();

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

        return $this->render('AgenceBundle:Default:add-user.html.twig',array(
            'agence' => $agence
        ));
    }
}
