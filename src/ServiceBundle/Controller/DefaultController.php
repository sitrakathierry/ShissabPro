<?php

namespace ServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Service;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ServiceBundle:Default:index.html.twig');
    }

    public function addAction()
    {
        return $this->render('ServiceBundle:Default:add.html.twig');
    }

    public function saveAction(Request $request)
    {
    	$id = $request->request->get('id');
    	$nom = $request->request->get('nom');
    	$description = $request->request->get('description');
    	$statut = $request->request->get('statut');

    	$user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        if ($id) {
        	$service = $this->getDoctrine()
	    		->getRepository('AppBundle:Service')
	            ->find($id);
        } else {
        	$service = new Service();
        }

        $service->setNom($nom);
    	$service->setDescription($description);
    	$service->setStatut($statut);
        $service->setAgence($agence);

    	$em = $this->getDoctrine()->getManager();
        $em->persist($service);
        $em->flush();

        return new JsonResponse(array(
        	'id' => $service->getId()
        ));
        

    }

    public function consultationAction()
    {
        $agences  = $this->getDoctrine()
                        ->getRepository('AppBundle:Agence')
                        ->findAll();

        $permission_user = $this->get('app.permission_user');
        $user = $this->getUser();
        $permissions = $permission_user->getPermissions($user);

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        return $this->render('ServiceBundle:Default:consultation.html.twig',array(
            'agences' => $agences,
            'userAgence' => $userAgence,
        ));
    }

    public function listAction(Request $request)
    {
        $agence = $request->request->get('agence');
        $recherche_par = $request->request->get('recherche_par');
        $a_rechercher = $request->request->get('a_rechercher');

        $services  = $this->getDoctrine()
                        ->getRepository('AppBundle:Service')
                        ->getList($agence,
                            $recherche_par,
                            $a_rechercher
                        );

        return new JsonResponse($services);
    }
    
}
