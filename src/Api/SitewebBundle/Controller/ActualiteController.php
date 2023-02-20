<?php

namespace Api\SitewebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class ActualiteController extends Controller
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
                ->getRepository('AppBundle:Actualite')
                ->list($key);

        return new JsonResponse($result);
	}

	public function detailsAction($id)
	{
		$actualite = $this->getDoctrine()
                ->getRepository('AppBundle:Actualite')
                ->details($id);

        return new JsonResponse($actualite);
	}
}
