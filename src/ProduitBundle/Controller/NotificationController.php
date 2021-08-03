<?php

namespace ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class NotificationController extends Controller
{
	public function listAction(Request $request)
	{
		$type_reponse = $request->request->get('type_reponse');

		$user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

		$produits = $this->getDoctrine()
                    ->getRepository('AppBundle:Produit')
                    ->notifications($agence->getId());

        if ($type_reponse == 'html') {
	        return $this->render('ProduitBundle:Notification:notification.html.twig',array(
	        	'produits' => $produits
	        ));
        } else {
        	return new JsonResponse($produits);
        }
	}
}
