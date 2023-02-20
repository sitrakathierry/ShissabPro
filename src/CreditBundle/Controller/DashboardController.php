<?php

namespace CreditBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
	public function indexAction()
    {

    	$total = $this->total(array(
            'statut' => 100
        ), true);

        $totalPaye = $this->total(array(
            'statut' => 2,
            'statut_paiement' => 1,
        ), true);

        $encoursPaiement = $this->total(array(
            'statut' => 2,
            'statut_paiement' => 0,
        ));

        $nbPaye = $this->total(array(
            'statut' => 2,
            'statut_paiement' => 1,
        ));

        $donut = array(
            array(
                'label' => 'Nb Dossier en cours',
                'value' => $encoursPaiement,
            ),
            array(
                'label' => 'Nb Dossier payé',
                'value' => $nbPaye,
            ),
        );

        return $this->render('CreditBundle:Dashboard:index.html.twig',array(
            'total' => $total,
            'totalPaye' => $totalPaye,
            'encoursPaiement' => $encoursPaiement,
            'nbPaye' => $nbPaye,
            'donut' => json_encode($donut),
        ));
    }

    public function total($params, $montant = false)
    {
    	$user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $agence = $userAgence->getAgence();

        $credits = $this->getDoctrine()
                        ->getRepository('AppBundle:Credit')
                        ->consultation(
                            $agence->getId(),
                            $this->getParams($params, 'statut'),
                            $this->getParams($params, 'statut_paiement')
                        );

        if ($montant) {
            if (!empty($credits)) {
            	return array_sum( array_column($credits, 'montant_total') );
            }
        } else {
            return count($credits);
        }

        return 0;
    }

    public function getParams($params, $index, $default = '')
    {
        if (array_key_exists($index, $params)) {
            return $params[$index];
        }

        return $default;
    }
}
