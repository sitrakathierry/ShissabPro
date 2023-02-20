<?php

namespace HebergementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
	public function indexAction()
    {

    	$journalier = $this->totalReservations(array(
            'statut' => 4,
            'type_date' => 1
        ));

        $mensuelle = $this->totalReservations(array(
            'statut' => 4,
            'type_date' => 2,
            'mois' => (new \DateTime('now'))->format('m') ,
            'annee' => (new \DateTime('now'))->format('Y') ,
        ));

        $annuelle = $this->totalReservations(array(
            'statut' => 4,
            'type_date' => 3,
            'annee' => (new \DateTime('now'))->format('Y') 
        ));

        $nonConfirmes = $this->totalReservations(array(
            'statut' => 0
        ));

    	$remboursements = $this->totalRemboursements(4);

        $payes = $this->totalReservations(array(
            'statut' => 4
        ));

        $line = $this->makeLineChart();

    	// $nonConfirmes = $this->totalReservations(0);
    	// $payes = $this->totalReservations(4);

        return $this->render('HebergementBundle:Dashboard:index.html.twig',array(
        	'journalier' => $journalier,
            'mensuelle' => $mensuelle,
            'annuelle' => $annuelle,
            'nonConfirmes' => $nonConfirmes,
            'remboursements' => $remboursements,
            'retenus' => $payes - $remboursements,
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
                $mensuelle = $this->totalReservations(array(
                    'statut' => 4,
                    'type_date' => 2,
                    'mois' => $month ,
                    'annee' => (new \DateTime('now'))->format('Y') ,
                ));

                $item = array(
                    'y' => $year . '-' . $month,
                    'value' => $mensuelle,
                );

                array_push($data, $item);
            }

        }

        return $data;

    }

    public function totalReservations($params)
    {
    	$user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $agence = $userAgence->getAgence();


        $bookings  = $this->getDoctrine()
                        ->getRepository('AppBundle:Booking')
                        ->getList(
                            $agence->getId(),
                            '',
                            '',
                            0,
                            $this->getParams($params, 'statut'),
                            0,
                            $this->getParams($params, 'type_date'),
                            $this->getParams($params, 'mois'),
                            $this->getParams($params, 'annee')
                        );

        if (!empty($bookings)) {
        	return array_sum( array_column($bookings, 'total') );
        }

    	return 0;
    }

    public function totalRemboursements()
    {
    	$user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $agence = $userAgence->getAgence();


        $bookings  = $this->getDoctrine()
                        ->getRepository('AppBundle:Booking')
                        ->getList(
                            $agence->getId(),
                            '',
                            '',
                            0
                        );

        if (!empty($bookings)) {
        	return array_sum( array_column($bookings, 'montant_remboursement') );
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
