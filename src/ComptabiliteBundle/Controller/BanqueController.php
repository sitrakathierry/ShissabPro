<?php

namespace ComptabiliteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Banque;

class BanqueController extends Controller
{
	public function indexAction()
    {
        return $this->render('ComptabiliteBundle:Banque:index.html.twig');
    }

    public function listAction()
    {
        return $this->render('ComptabiliteBundle:Banque:list.html.twig');
    }

    public function getListAction(Request $request)
    {

        $banques = $this->getDoctrine()
                ->getRepository('AppBundle:Banque')
                ->list();

        return new JsonResponse($banques);
    }

    public function saveAction(Request $request)
    {
    	$nom = $request->request->get('nom');
        $id = $request->request->get('id');

        if ($id) {
        	$banque = $this->getDoctrine()
                    ->getRepository('AppBundle:Banque')
                    ->find($id);
        } else {
        	$banque = new Banque();
        }

        $banque->setNom($nom);

        $em = $this->getDoctrine()->getManager();
        $em->persist($banque);
        $em->flush();

        if ($banque->getId()) {
        	return new Response(200);
        }
    	
    }

    public function deleteAction($id)
    {
        $banque  = $this->getDoctrine()
                        ->getRepository('AppBundle:Banque')
                        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($banque);
        $em->flush();

        return new JsonResponse(200);
        
    }

    public function editorAction(Request $request)
    {
        $id = $request->request->get('id');
        $banque = $this->getDoctrine()->getRepository('AppBundle:Banque')
            ->find($id);

        return $this->render('@Comptabilite/Banque/editor.html.twig',[
            'banque' => $banque,
        ]);
    }
}
