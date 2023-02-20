<?php

namespace SitewebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Actualite;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ActualiteController extends Controller
{
	public function indexAction($id)
    {
        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id);

        return $this->render('SitewebBundle:Actualite:index.html.twig',array(
            'siteweb' => $siteweb
        ));
    }

    public function saveAction(Request $request)
    {
        $id = $request->request->get('id');
        $id_siteweb = $request->request->get('id_siteweb');
        $titre = $request->request->get('titre');
        $description = $request->request->get('description');
        $actualite_img = $request->request->get('actualite_img');

        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id_siteweb);

        if ($id) {
            $actualite = $this->getDoctrine()
                ->getRepository('AppBundle:Actualite')
                ->find($id);
            $date = $actualite->getDate();
        } else {
            $actualite = new Actualite();
            $date = new \DateTime('now');
        }

        $actualite->setTitre($titre);
        $actualite->setDescription($description);
        $actualite->setImg($actualite_img);
        $actualite->setSiteweb($siteweb);
        $actualite->setDate($date);

        $em = $this->getDoctrine()->getManager();
        $em->persist($actualite);
        $em->flush();

        return new JsonResponse(array(
            'id' => $actualite->getId()
        ));
        
    }

    public function listAction(Request $request)
    {
        $id_siteweb = $request->request->get('id_siteweb');

        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id_siteweb);

        $cle = $siteweb->getCle();

        $actualites = $this->getDoctrine()
                ->getRepository('AppBundle:Actualite')
                ->list($cle);

        return new JsonResponse($actualites);

        
    }

    public function showAction($id)
    {
        $actualite = $this->getDoctrine()
                ->getRepository('AppBundle:Actualite')
                ->find($id);

        $siteweb = $actualite->getSiteweb();

        return $this->render('SitewebBundle:Actualite:show.html.twig',array(
            'actualite' => $actualite,
            'siteweb' => $siteweb,
        ));
    }

}
