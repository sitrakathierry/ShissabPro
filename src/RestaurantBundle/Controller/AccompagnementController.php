<?php

namespace RestaurantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Accompagnement;

class AccompagnementController extends Controller
{


	public function indexAction()
    {

    	$user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        return $this->render('RestaurantBundle:Accompagnement:index.html.twig',array(
        ));
    }

    public function listAction(Request $request)
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence_id = $userAgence->getAgence()->getId();

        $accompagnements = $this->getDoctrine()
                ->getRepository('AppBundle:Accompagnement')
                ->list($agence_id);

        return new JsonResponse($accompagnements);
    }

    public function saveAction(Request $request)
    {
        $nom = $request->request->get('nom');
        $description = $request->request->get('description');
        $id = $request->request->get('id');
        $prix = $request->request->get('prix');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        if ($id) {
            $accompagnement = $this->getDoctrine()
                    ->getRepository('AppBundle:Accompagnement')
                    ->find($id);
        } else {
            $accompagnement = new Accompagnement();
        }

        $accompagnement->setNom($nom);
        $accompagnement->setDescription($description);
        $accompagnement->setPrix($prix);
        $accompagnement->setAgence($agence);

        $em = $this->getDoctrine()->getManager();
        $em->persist($accompagnement);
        $em->flush();

        if ($accompagnement->getId()) {
            return new Response(200);
        }
        
    }

    public function deleteAction($id)
    {
        $accompagnement  = $this->getDoctrine()
                        ->getRepository('AppBundle:Accompagnement')
                        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($accompagnement);
        $em->flush();

        return new JsonResponse(200);
        
    }

    public function editorAction(Request $request)
    {
        $id = $request->request->get('id');
        $accompagnement = $this->getDoctrine()
        	->getRepository('AppBundle:Accompagnement')
            ->find($id);

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        return $this->render('@Restaurant/Accompagnement/editor.html.twig',[
            'accompagnement' => $accompagnement,
        ]);
    }


}
