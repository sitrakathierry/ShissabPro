<?php

namespace ComptabiliteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DepenseController extends Controller
{
	public function indexAction()
    {
        return $this->render('ComptabiliteBundle:Depense:index.html.twig');
    }

    public function chargerFournisseurAction()
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
            ->getRepository('AppBundle:UserAgence')
            ->findOneBy(array(
                'user' => $user
            ));
        $agence = $userAgence->getAgence();
        $agenceId = $agence->getId();
        $fournisseurs = $this->getDoctrine()
            ->getRepository('AppBundle:Fournisseur')
            ->list($agenceId);

        return new JsonResponse($fournisseurs);
    }
}
