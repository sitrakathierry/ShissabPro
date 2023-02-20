<?php

namespace BonCommandeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
	public function indexAction()
    {

    	$total = $this->total(true);

        $nb = $this->total();

        return $this->render('BonCommandeBundle:Dashboard:index.html.twig',array(
            'total' => $total,
            'nb' => $nb,
        ));
    }

    public function total($montant = false)
    {
    	$user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $agence = $userAgence->getAgence();

        $bons = $this->getDoctrine()
                    ->getRepository('AppBundle:BonCommande')
                    ->consultation($agence->getId());

        if ($montant) {
            if (!empty($bons)) {
            	return array_sum( array_column($bons, 'montant_total') );
            }
        } else {
            return count($bons);
        }


        return 0;
    }
}
