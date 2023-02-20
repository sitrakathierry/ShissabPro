<?php

namespace Api\SitewebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class SitewebController extends Controller
{
	public function detailsAction($key)
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
                ->getRepository('AppBundle:Siteweb')
                ->details($key);

        return new JsonResponse($result);
	}

	public function aproposAction($key)
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
                ->getRepository('AppBundle:SitewebApropos')
                ->apropos($key);

        return new JsonResponse($result);
	}

    public function sectionsAction($key)
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

        $sections = $this->getDoctrine()
                ->getRepository('AppBundle:Section')
                ->findBy(array(
                    'siteweb' => $siteweb
                ));

        $results = [];

        foreach ($sections as $section) {
            
            $slug = $section->getSlug();

            $value = $this->getDoctrine()
                ->getRepository('AppBundle:SectionValeur')
                ->findOneBy(array(
                    'section' => $section
                ));

            $item = array(
                'id' => $section->getId(),
                'slug' => $slug,
                'valeur' => $value ? $value->getValeur() : '',
            );

            // $results[$slug] = $value ? $value->getValeur() : '';

            
            array_push($results, $item);            

        }

        return new JsonResponse($results);
    }
}
