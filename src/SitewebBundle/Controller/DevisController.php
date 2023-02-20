<?php

namespace SitewebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DevisController extends Controller
{
	public function indexAction($id)
    {
        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id);

        $devisList = $this->getDoctrine()
                ->getRepository('AppBundle:Devis')
                ->findBy(array(
                    'siteweb' => $siteweb
                ));


        return $this->render('SitewebBundle:Devis:index.html.twig',array(
            'siteweb' => $siteweb,
            'devisList' => $devisList,
        ));
    }

    public function listAction(Request $request)
    {
        $id_siteweb = $request->request->get('id_siteweb');

        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id_siteweb);

        $cle = $siteweb->getCle();

        $devis = $this->getDoctrine()
                ->getRepository('AppBundle:Devis')
                ->list($cle);

        return new JsonResponse($devis);

        
    }

    public function showAction($id)
    {
        $devis = $this->getDoctrine()
                ->getRepository('AppBundle:Devis')
                ->find($id);

        $siteweb = $devis->getSiteweb();

        return $this->render('SitewebBundle:Devis:show.html.twig',array(
            'devis' => $devis,
            'siteweb' => $siteweb,
        ));
    }
}
