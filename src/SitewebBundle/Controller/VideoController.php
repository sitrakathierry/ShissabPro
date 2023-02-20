<?php

namespace SitewebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Video;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class VideoController extends Controller
{


	public function indexAction($id)
    {
        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id);

        return $this->render('SitewebBundle:Video:index.html.twig',array(
            'siteweb' => $siteweb
        ));
    }

    public function saveAction(Request $request)
    {
        $id = $request->request->get('id');
        $id_siteweb = $request->request->get('id_siteweb');
        $titre = $request->request->get('titre');
        $url = $request->request->get('url');

        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id_siteweb);

        if ($id) {
            $video = $this->getDoctrine()
                ->getRepository('AppBundle:Video')
                ->find($id);
        } else {
            $video = new Video();
        }

        $video->setTitre($titre);
        $video->setUrl($url);
        $video->setSiteweb($siteweb);

        $em = $this->getDoctrine()->getManager();
        $em->persist($video);
        $em->flush();

        return new JsonResponse(array(
            'id' => $video->getId()
        ));
        
    }

    public function listAction(Request $request)
    {
        $id_siteweb = $request->request->get('id_siteweb');

        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id_siteweb);

        $cle = $siteweb->getCle();

        $videos = $this->getDoctrine()
                ->getRepository('AppBundle:Video')
                ->list($cle);

        return new JsonResponse($videos);

        
    }

    public function showAction($id)
    {
        $video = $this->getDoctrine()
                ->getRepository('AppBundle:Video')
                ->find($id);

        $siteweb = $video->getSiteweb();

        return $this->render('SitewebBundle:Video:show.html.twig',array(
            'video' => $video,
            'siteweb' => $siteweb,
        ));
    }

    public function deleteAction($id)
    {
        $video  = $this->getDoctrine()
                        ->getRepository('AppBundle:Video')
                        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($video);
        $em->flush();

        return new JsonResponse(200);
    }


}
