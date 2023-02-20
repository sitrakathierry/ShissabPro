<?php

namespace HebergementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\TypeChambre;

class TypeController extends Controller
{
	public function indexAction()
    {
        return $this->render('HebergementBundle:Type:index.html.twig');
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

        $types = $this->getDoctrine()
                ->getRepository('AppBundle:TypeChambre')
                ->list($agence_id);

        return new JsonResponse($types);
    }

    public function saveAction(Request $request)
    {
        $nom = $request->request->get('nom');
        $description = $request->request->get('description');
        $id = $request->request->get('id');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        if ($id) {
            $type = $this->getDoctrine()
                    ->getRepository('AppBundle:TypeChambre')
                    ->find($id);
        } else {
            $type = new TypeChambre();
        }

        $type->setNom($nom);
        $type->setDescription($description);
        $type->setAgence($agence);

        $em = $this->getDoctrine()->getManager();
        $em->persist($type);
        $em->flush();

        if ($type->getId()) {
            return new Response(200);
        }
        
    }

    public function deleteAction($id)
    {
        $type  = $this->getDoctrine()
                        ->getRepository('AppBundle:TypeChambre')
                        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($type);
        $em->flush();

        return new JsonResponse(200);
        
    }

    public function editorAction(Request $request)
    {
        $id = $request->request->get('id');
        $type = $this->getDoctrine()->getRepository('AppBundle:TypeChambre')
            ->find($id);

        return $this->render('@Hebergement/Type/editor.html.twig',[
            'type' => $type,
        ]);
    }
}
