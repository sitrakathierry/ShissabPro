<?php

namespace SitewebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Slider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class SliderController extends Controller
{
	public function indexAction($id)
    {
        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id);

        return $this->render('SitewebBundle:Slider:index.html.twig',array(
            'siteweb' => $siteweb
        ));
    }

    public function saveAction(Request $request)
    {
        $id = $request->request->get('id');
        $id_siteweb = $request->request->get('id_siteweb');
        $titre = $request->request->get('titre');
        $sous_titre = $request->request->get('sous_titre');
        $slider_img = $request->request->get('slider_img');

        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id_siteweb);

        if ($id) {
            $slider = $this->getDoctrine()
                ->getRepository('AppBundle:Slider')
                ->find($id);
        } else {
            $slider = new Slider();
        }

        $slider->setTitre($titre);
        $slider->setSousTitre($sous_titre);
        $slider->setImg($slider_img);
        $slider->setSiteweb($siteweb);

        $em = $this->getDoctrine()->getManager();
        $em->persist($slider);
        $em->flush();

        return new JsonResponse(array(
            'id' => $slider->getId()
        ));
        
    }

    public function listAction(Request $request)
    {
        $id_siteweb = $request->request->get('id_siteweb');

        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id_siteweb);

        $cle = $siteweb->getCle();

        $sliders = $this->getDoctrine()
                ->getRepository('AppBundle:Slider')
                ->list($cle);

        return new JsonResponse($sliders);

        
    }

    public function showAction($id)
    {
        $slider = $this->getDoctrine()
                ->getRepository('AppBundle:Slider')
                ->find($id);

        $siteweb = $slider->getSiteweb();

        return $this->render('SitewebBundle:Slider:show.html.twig',array(
            'slider' => $slider,
            'siteweb' => $siteweb,
        ));
    }

    public function deleteAction($id)
    {
        $slider  = $this->getDoctrine()
                        ->getRepository('AppBundle:Slider')
                        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($slider);
        $em->flush();

        return new JsonResponse(200);
    }

    public function disableAction($id)
    {
        $slider = $this->getDoctrine()
                ->getRepository('AppBundle:Slider')
                ->find($id);

        $slider->setDesactive(1);

        $em = $this->getDoctrine()->getManager();
        $em->persist($slider);
        $em->flush();

        return new JsonResponse(array(
            'id' => $slider->getId()
        ));
        
    }

    public function enableAction($id)
    {
        $slider = $this->getDoctrine()
                ->getRepository('AppBundle:Slider')
                ->find($id);

        $slider->setDesactive('');

        $em = $this->getDoctrine()->getManager();
        $em->persist($slider);
        $em->flush();

        return new JsonResponse(array(
            'id' => $slider->getId()
        ));
        
    }


}
