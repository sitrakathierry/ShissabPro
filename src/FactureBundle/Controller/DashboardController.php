<?php

namespace FactureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
	public function indexAction()
    {
    	$totalDevis = $this->totalFactures(array(
    		'type' => 1
    	));

    	$totalDevisMois = $this->totalFactures(array(
    		'type' => 1,
    		'type_date' => 2,
    		'mois' => (new \DateTime('now'))->format('m') ,
    		'annee' => (new \DateTime('now'))->format('Y') ,
    	));

    	$montantTotalDevis = $this->totalFactures(array(
    		'type' => 1
    	), true);

    	$totalDefinitive = $this->totalFactures(array(
    		'type' => 2
    	));

    	$totalDefinitiveMois = $this->totalFactures(array(
    		'type' => 2,
    		'type_date' => 2,
    		'mois' => (new \DateTime('now'))->format('m') ,
    		'annee' => (new \DateTime('now'))->format('Y') ,
    	));

    	$montantTotalDefinitive = $this->totalFactures(array(
    		'type' => 2
    	), true);

        $pie = array(
            array(
                'label' => 'Total devis',
                'data' => $totalDevis,
                'color' => '#1c84c6',
            ),
            array(
                'label' => 'Total définitive',
                'data' => $totalDefinitive,
                'color' => '#23c6c8',
            )
        );

        $morris = array(
            array(
                'y' => 'Montant total',
                'a' => $montantTotalDevis,
                'b' => $montantTotalDefinitive,
            )
        );

        $line = $this->makeLineChart();

        return $this->render('FactureBundle:Dashboard:index.html.twig',array(
        	'totalDevis' => $totalDevis,
        	'totalDevisMois' => $totalDevisMois,
        	'montantTotalDevis' => $montantTotalDevis,
        	'totalDefinitive' => $totalDefinitive,
        	'totalDefinitiveMois' => $totalDefinitiveMois,
        	'montantTotalDefinitive' => $montantTotalDefinitive,
            'pie' => json_encode($pie),
            'morris' => json_encode($morris),
            'line' => json_encode($line),
        ));
    }

    public function makeLineChart()
    {
        $year = (new \DateTime('now'))->format('Y');
        $current = (new \DateTime('now'))->format('m');

        $months = array('01','02','03','04','05','06','07','08','09','10','11','12');

        $data = [];

        foreach ($months as $key => $month) {

            if (intval($month) <= intval($current)) {
                $devis = $this->totalFactures(array(
                    'type' => 1,
                    'type_date' => 2,
                    'mois' => $month ,
                    'annee' => (new \DateTime('now'))->format('Y') ,
                ), true);

                $definitif = $this->totalFactures(array(
                    'type' => 2,
                    'type_date' => 2,
                    'mois' => $month ,
                    'annee' => (new \DateTime('now'))->format('Y') ,
                ), true);


                $item = array(
                    'y' => $year . '-' . $month,
                    'a' => $devis,
                    'b' => $definitif
                );

                array_push($data, $item);
            }

        }

        return $data;

    }

    public function totalFactures($params, $montant = false)
    {

    	$user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $agence = $userAgence->getAgence();

    	$factures = $this->getDoctrine()
            ->getRepository('AppBundle:Facture')
            ->consultation(
                '',
                '',
                $this->getParams($params, 'type_date'),
                $this->getParams($params, 'mois'),
                $this->getParams($params, 'annee'),
                '',
                '',
                '',
                $agence->getId(),
                0,
                $this->getParams($params, 'type')
            );

        if (!$montant) {
        	return count($factures);
        } else {
	        if (!empty($factures)) {
	            return array_sum( array_column($factures, 'total') );
	        }
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
