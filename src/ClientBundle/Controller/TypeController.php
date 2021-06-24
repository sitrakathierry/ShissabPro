<?php

namespace ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\TypeSociete;
use AppBundle\Entity\TypeSocial;
use Symfony\Component\HttpFoundation\Response;

class TypeController extends Controller
{
	
    /**
     * Type de société
     */

    public function addTypeSocieteAction()
    {
        return $this->render('@Client/TypeSociete/add.html.twig');
    }

    public function saveTypeSocieteAction(Request $request)
    {
        $designation = $request->request->get('designation');
        $description = $request->request->get('description');
        $id = $request->request->get('id');

        if ($id) {
            $type = $this->getDoctrine()
                ->getRepository('AppBundle:TypeSociete')
                ->find($id);
        } else{
            $type = new TypeSociete();
        }


        $type->setDesignation($designation);
        $type->setDesc($description);


        $em = $this->getDoctrine()->getManager();
        $em->persist($type);
        $em->flush();

        return $this->redirectToRoute('typesociete_show',array(
            'id'  => $type->getId()
        ));

    }

    public function showTypeSocieteAction($id)
    {
        $type  = $this->getDoctrine()
                        ->getRepository('AppBundle:TypeSociete')
                        ->find($id);

        return $this->render('ClientBundle:TypeSociete:show.html.twig', array(
            'type' => $type,
        ));
    }

    public function listTypeSocieteAction()
    {
        return $this->render('@Client/TypeSociete/list.html.twig');
    }

    public function getListeTypeSocieteAction(Request $request)
    {

        $types = $this->getDoctrine()
                ->getRepository('AppBundle:TypeSociete')
                ->list();

        return new JsonResponse($types);
    }

    /*
     * Type social
     */
    public function addTypeSocialAction()
    {
        return $this->render('@Client/TypeSocial/add.html.twig');
    }

    public function saveTypeSocialAction(Request $request)
    {
        $designation = $request->request->get('designation');
        $description = $request->request->get('description');
        $id = $request->request->get('id');

        if ($id) {
            $type = $this->getDoctrine()
                ->getRepository('AppBundle:TypeSocial')
                ->find($id);
        } else{
            $type = new TypeSocial();
        }


        $type->setDesignation($designation);
        $type->setDesc($description);


        $em = $this->getDoctrine()->getManager();
        $em->persist($type);
        $em->flush();

        return $this->redirectToRoute('typesocial_show',array(
            'id'  => $type->getId()
        ));

    }

    public function showTypeSocialAction($id)
    {
        $type  = $this->getDoctrine()
                        ->getRepository('AppBundle:TypeSocial')
                        ->find($id);

        return $this->render('ClientBundle:TypeSocial:show.html.twig', array(
            'type' => $type,
        ));
    }

    public function listTypeSocialAction()
    {
        return $this->render('@Client/TypeSocial/list.html.twig');
    }

    public function getListeTypeSocialAction(Request $request)
    {

        $types = $this->getDoctrine()
                ->getRepository('AppBundle:TypeSocial')
                ->list();

        return new JsonResponse($types);
    }

    public function editorTypeSocieteAction(Request $request)
    {
        $id = $request->request->get('id');
        $type = $this->getDoctrine()->getRepository('AppBundle:TypeSociete')
            ->find($id);

        return $this->render('@Client/TypeSociete/editor.html.twig',[
            'type' => $type,
        ]);
    }

    public function deleteTypeSocieteAction($id)
    {
        $type  = $this->getDoctrine()
                        ->getRepository('AppBundle:TypeSociete')
                        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($type);
        $em->flush();

        return new JsonResponse(200);
        
    }

    public function editorTypeSocialAction(Request $request)
    {
        $id = $request->request->get('id');
        $type = $this->getDoctrine()->getRepository('AppBundle:TypeSocial')
            ->find($id);

        return $this->render('@Client/TypeSocial/editor.html.twig',[
            'type' => $type,
        ]);
    }

    public function deleteTypeSocialAction($id)
    {
        $type  = $this->getDoctrine()
                        ->getRepository('AppBundle:TypeSocial')
                        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($type);
        $em->flush();

        return new JsonResponse(200);
        
    }

}
