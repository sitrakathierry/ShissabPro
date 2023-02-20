<?php

namespace RestaurantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
	public function indexAction()
    {

        $nbClientsJournalier = $this->totalVentes(array(
            'type_date' => 1
        ));

        $nbPlatsJournalier = $this->totalVentes(array(
            'type_date' => 1,
            'type_plat' => 1,
            'count' => 'nb_plat'
        ));

        $totalPlatsReservation = $this->totalVentes(array(
            'type_commande' => 1,
        ), true);

        $totalPlatsEmporte = $this->totalVentes(array(
            'type_commande' => 2,
        ), true);

        $totalJournalier = $this->totalVentes(array(
            'type_date' => 1,
        ), true);

        $recettePlat = $this->totalVentes(array(
            'type_plat' => 1,
            'count' => 'total_details',
        ), true);

        $recetteBoisson = $this->totalVentes(array(
            'type_plat' => 2,
            'count' => 'total_details',
        ), true);

        $line = $this->makeLineChart();

    	// $approvisionnements = $this->totalApprovisionnements();

        return $this->render('RestaurantBundle:Dashboard:index.html.twig',array(
            'nbClientsJournalier' => $nbClientsJournalier,
            'nbPlatsJournalier' => $nbPlatsJournalier,
            'totalPlatsReservation' => $totalPlatsReservation,
            'totalPlatsEmporte' => $totalPlatsEmporte,
            'totalJournalier' => $totalJournalier,
            'recettePlat' => $recettePlat,
            'recetteBoisson' => $recetteBoisson,
            'line' => json_encode($line)
        	// 'approvisionnements' => $approvisionnements,
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
                $mensuelle = $this->totalVentes(array(
                    'type_date' => 2,
                    'mois' => $month ,
                    'annee' => (new \DateTime('now'))->format('Y') ,
                ), true);

                $item = array(
                    'y' => $year . '-' . $month,
                    'value' => $mensuelle,
                );

                array_push($data, $item);
            }

        }

        return $data;

    }

    public function totalVentes($params, $montant = false)
    {
        $user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $agence = $userAgence->getAgence();


        $type_commande = $this->getParams($params, 'type_commande');

        if ($type_commande == 2) {
            $reservations = [];
        } else {
            $reservations = $this->getDoctrine()
                            ->getRepository('AppBundle:Reservation')
                            ->consultation(
                                $agence->getId(),
                                200,
                                0,
                                0,
                                0,
                                $this->getParams($params, 'type_date'),
                                $this->getParams($params, 'mois'),
                                $this->getParams($params, 'annee')
                            );
        }

        if ($type_commande == 1) {
            $emporters = [];
        } else {
            $emporters = $this->getDoctrine()
                        ->getRepository('AppBundle:Emporter')
                        ->consultation(
                            $agence->getId(),
                            200,
                            0,
                            0,
                            0,
                            $this->getParams($params, 'type_date'),
                                $this->getParams($params, 'mois'),
                                $this->getParams($params, 'annee')
                        );
        }
        

        $commandes = array_merge($reservations, $emporters);

        $count = $this->getParams($params, 'count');
        $type_plat = $this->getParams($params, 'type_plat');

        if ($count) {

            $doctrine = $this->getDoctrine();

            if (!empty($reservations)) {
                $reservations = array_map(function($reservation) use ($doctrine, $type_plat)
                {

                    $details = $doctrine
                        ->getRepository('AppBundle:ReservationDetails')
                        ->consultation($reservation['id'], $type_plat);

                    $reservation['details'] = null;

                    if (!empty($details)) {
                        $reservation['details'] = $details;
                        $reservation['nb'] = count($details);
                        $reservation['total_details'] = array_sum( array_column($details, 'total') );

                    }

                    return $reservation;

                }, $reservations);
            }

            if (!empty($emporters)) {
                $emporters = array_map(function($emporter) use ($doctrine, $type_plat)
                {
                    $details = $doctrine
                            ->getRepository('AppBundle:EmporterDetails')
                            ->consultation($emporter['id'], $type_plat);


                    $emporter['details'] = null;

                    if (!empty($details)) {
                        $emporter['details'] = $details;
                        $emporter['nb'] = count($details);
                        $emporter['total_details'] = array_sum( array_column($details, 'total') );

                    }

                    return $emporter;

                }, $emporters);
            }

            $commandes = array_merge($reservations, $emporters);

            switch ($count) {
                case 'nb_plat':
                    return array_sum( array_column($commandes, 'nb') );
                    break;
                case 'total_details':
                    return array_sum( array_column($commandes, 'total_details') );
                    break;
            }
        }

        if (!$montant) {
            return count($commandes);
        } else {
            if (!empty($commandes)) {
                return array_sum( array_column($commandes, 'total') );
            }
        }


        return 0;
    }

    public function totalApprovisionnements()
    {

        $user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $agence = $userAgence->getAgence();

        $achats = $this->getDoctrine()
                ->getRepository('AppBundle:EntreeSortieStockInterneDetails')
                ->consultation(
                    $agence->getId(),
                    false,
                    1
                );

        if (!empty($achats)) {
            return array_sum( array_column($achats, 'total') );
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
