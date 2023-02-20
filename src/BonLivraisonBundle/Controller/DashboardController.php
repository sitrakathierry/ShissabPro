<?php

namespace BonLivraisonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
	public function indexAction()
    {

    	$total = $this->total();

        return $this->render('BonLivraisonBundle:Dashboard:index.html.twig',array(
            'total' => $total,
        ));
    }

    public function total()
    {
    	$user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $agence = $userAgence->getAgence();

        $bons = $this->getDoctrine()
                    ->getRepository('AppBundle:BonLivraison')
                    ->consultation($agence->getId());

        return count($bons);
    }
}
