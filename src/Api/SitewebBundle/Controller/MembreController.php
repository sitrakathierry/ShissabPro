<?php

namespace Api\SitewebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class MembreController extends Controller
{
	public function listAction($key)
	{

		$siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->findOneBy(array(
                	'cle' => $key
                ));

        if (!$siteweb) {
            return new JsonResponse(array(
                'status' => 204,
                'message' => 'Invalid sitekey',
            ));
        }
        
		$result = $this->getDoctrine()
                ->getRepository('AppBundle:Membre')
                ->list($key);

        return new JsonResponse($result);
	}

	public function detailsAction($id)
	{
		$membre = $this->getDoctrine()
                ->getRepository('AppBundle:Membre')
                ->details($id);

        return new JsonResponse($membre);
	}
}
