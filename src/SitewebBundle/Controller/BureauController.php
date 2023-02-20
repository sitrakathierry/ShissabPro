<?php

namespace SitewebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Bureau;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class BureauController extends Controller
{
	public function indexAction($id)
    {
        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id);

        return $this->render('SitewebBundle:Bureau:index.html.twig',array(
            'siteweb' => $siteweb
        ));
    }

    public function saveAction(Request $request)
    {
        $id = $request->request->get('id');
        $id_siteweb = $request->request->get('id_siteweb');
        $nom = $request->request->get('nom');
        $adresse = $request->request->get('adresse');
        $tel = $request->request->get('tel');
        $email = $request->request->get('email');
        $bureau_img = $request->request->get('bureau_img');

        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id_siteweb);

        if ($id) {
            $bureau = $this->getDoctrine()
                ->getRepository('AppBundle:Bureau')
                ->find($id);
        } else {
            $bureau = new Bureau();
        }

        $bureau->setNom($nom);
        $bureau->setAdresse($adresse);
        $bureau->setTel($tel);
        $bureau->setEmail($email);
        $bureau->setImg($bureau_img);
        $bureau->setSiteweb($siteweb);

        $em = $this->getDoctrine()->getManager();
        $em->persist($bureau);
        $em->flush();

        return new JsonResponse(array(
            'id' => $bureau->getId()
        ));
        
    }

    public function listAction(Request $request)
    {
        $id_siteweb = $request->request->get('id_siteweb');

        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id_siteweb);

        $cle = $siteweb->getCle();

        $bureaux = $this->getDoctrine()
                ->getRepository('AppBundle:Bureau')
                ->list($cle);

        return new JsonResponse($bureaux);

        
    }

    public function showAction($id)
    {
        $bureau = $this->getDoctrine()
                ->getRepository('AppBundle:Bureau')
                ->find($id);

        $siteweb = $bureau->getSiteweb();

        return $this->render('SitewebBundle:Bureau:show.html.twig',array(
            'bureau' => $bureau,
            'siteweb' => $siteweb,
        ));
    }

    public function deleteAction($id)
    {
        $bureau  = $this->getDoctrine()
                        ->getRepository('AppBundle:Bureau')
                        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($bureau);
        $em->flush();

        return new JsonResponse(200);
    }
}
