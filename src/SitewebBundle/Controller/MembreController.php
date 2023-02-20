<?php

namespace SitewebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Membre;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class MembreController extends Controller
{
	public function indexAction($id)
    {
        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id);

        return $this->render('SitewebBundle:Membre:index.html.twig',array(
            'siteweb' => $siteweb
        ));
    }

    public function saveAction(Request $request)
    {
        $id = $request->request->get('id');
        $id_siteweb = $request->request->get('id_siteweb');
        $nom = $request->request->get('nom');
        $poste = $request->request->get('poste');
        $membre_img = $request->request->get('membre_img');

        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id_siteweb);

        if ($id) {
            $membre = $this->getDoctrine()
                ->getRepository('AppBundle:Membre')
                ->find($id);
        } else {
            $membre = new Membre();
        }

        $membre->setNom($nom);
        $membre->setPoste($poste);
        $membre->setImg($membre_img);
        $membre->setSiteweb($siteweb);

        $em = $this->getDoctrine()->getManager();
        $em->persist($membre);
        $em->flush();

        return new JsonResponse(array(
            'id' => $membre->getId()
        ));
        
    }

    public function listAction(Request $request)
    {
        $id_siteweb = $request->request->get('id_siteweb');

        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id_siteweb);

        $cle = $siteweb->getCle();

        $membres = $this->getDoctrine()
                ->getRepository('AppBundle:Membre')
                ->list($cle);

        return new JsonResponse($membres);

        
    }

    public function showAction($id)
    {
        $membre = $this->getDoctrine()
                ->getRepository('AppBundle:Membre')
                ->find($id);

        $siteweb = $membre->getSiteweb();

        return $this->render('SitewebBundle:Membre:show.html.twig',array(
            'membre' => $membre,
            'siteweb' => $siteweb,
        ));
    }

    public function deleteAction($id)
    {
        $membre  = $this->getDoctrine()
                        ->getRepository('AppBundle:Membre')
                        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($membre);
        $em->flush();

        return new JsonResponse(200);
    }

    public function disableAction($id)
    {
        $membre = $this->getDoctrine()
                ->getRepository('AppBundle:Membre')
                ->find($id);

        $membre->setDesactive(1);

        $em = $this->getDoctrine()->getManager();
        $em->persist($membre);
        $em->flush();

        return new JsonResponse(array(
            'id' => $membre->getId()
        ));
        
    }

    public function enableAction($id)
    {
        $membre = $this->getDoctrine()
                ->getRepository('AppBundle:Membre')
                ->find($id);

        $membre->setDesactive('');

        $em = $this->getDoctrine()->getManager();
        $em->persist($membre);
        $em->flush();

        return new JsonResponse(array(
            'id' => $membre->getId()
        ));
        
    }
}
