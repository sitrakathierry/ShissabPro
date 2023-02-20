<?php

namespace Api\SitewebBundle\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProduitController extends Controller
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
                ->getRepository('AppBundle:SitewebProduit')
                ->list($key);

        return new JsonResponse($result);
	}

	public function detailsAction($id)
	{
		$produit = $this->getDoctrine()
                ->getRepository('AppBundle:SitewebProduit')
                ->details($id);

        return new JsonResponse($produit);
	}
}
