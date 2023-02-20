<?php

namespace SitewebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ParametreController extends Controller
{
	public function indexAction($id)
    {
        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id);

        $config = $this->getDoctrine()
                ->getRepository('ApiConfigBundle:Config')
                ->findOneBy(array(
                    'siteweb' => $siteweb
                ));

        return $this->render('SitewebBundle:Parametre:index.html.twig',array(
            'siteweb' => $siteweb,
            'config' => $config,
        ));
    }
}
