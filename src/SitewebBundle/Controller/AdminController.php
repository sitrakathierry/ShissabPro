<?php

namespace SitewebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Autorisation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminController extends Controller
{
	public function indexAction()
    {

        return $this->render('SitewebBundle:Admin:index.html.twig',array(
        ));
    }

    public function autorisationAction($id)
    {
    	$siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id);

        $modules = $this->getDoctrine()
                ->getRepository('AppBundle:Module')
                ->findAll();

        $autorisations = $this->getDoctrine()
                ->getRepository('AppBundle:Autorisation')
                ->findBy(array(
                    'siteweb' => $siteweb
                ));

        return $this->render('SitewebBundle:Admin:autorisation.html.twig',array(
        	'siteweb' => $siteweb,
            'modules' => $modules,
            'autorisations' => $autorisations,
        ));
    }

    public function saveAction(Request $request)
    {
        $autorisations = $request->request->get('autorisations');
        $id_siteweb = $request->request->get('id_siteweb');

        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id_siteweb);

        $details = $this->getDoctrine()
                ->getRepository('AppBundle:Autorisation')
                ->findBy(array(
                   'siteweb' => $siteweb
                ));

        $em = $this->getDoctrine()->getManager();

        foreach ($details as $detail) {
            $em->remove($detail);
            $em->flush();
        }

        foreach ($autorisations as $module) {
            $module = $this->getDoctrine()
                ->getRepository('AppBundle:Module')
                ->find($module);

            $autorisation = new Autorisation();

            $autorisation->setModule($module);
            $autorisation->setSiteweb($siteweb);

            $em->persist($autorisation);
            $em->flush();

        }

        return new JsonResponse(array(
            'id' => $siteweb->getId()
        ));
        
    }
}
