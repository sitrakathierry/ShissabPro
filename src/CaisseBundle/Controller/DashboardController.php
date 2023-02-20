<?php

namespace CaisseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
	public function indexAction()
    {

    	$journalier = $this->totalVentes(array(
    		'ajourdhui' => true,
    	));

    	$mensuelle = $this->totalVentes(array(
    		'annee' => (new \DateTime('now'))->format('Y') ,
    		'mois' => (new \DateTime('now'))->format('m') ,
    	));

    	$annuelle = $this->totalVentes(array(
    		'annee' => (new \DateTime('now'))->format('Y') ,
    	));

        $line = $this->makeLineChart();

        return $this->render('CaisseBundle:Dashboard:index.html.twig',array(
            'journalier' => $journalier,
            'mensuelle' => $mensuelle,
            'annuelle' => $annuelle,
            'line' => json_encode($line),
        ));


    }

    public function makeLineChart()
    {
        $year = (new \DateTime('now'))->format('Y');
        $current = (new \DateTime('now'))->format('m');

        $months = array('01','02','03','04','05','06','07','08','09','10','11','12');

        $data = [];

        foreach ($months as $month) {
            if (intval($month) <= intval($current)) {
                $mensuelle = $this->totalVentes(array(
                    'annee' => (new \DateTime('now'))->format('Y') ,
                    'mois' => $month ,
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

    public function totalVentes($params = [])
    {
    	$user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $agence = $userAgence->getAgence();

        $role = $user->getRoles()[0];

        $id_entrepot = false;

        if ($role != "ROLE_RESPONSABLE") {
        	$userEntrepot = $this->getDoctrine()
                    ->getRepository('AppBundle:UserEntrepot')
                    ->findOneBy(array(
                        'user' => $user
                    ));

            $id_entrepot = $userEntrepot->getEntrepot()->getId();

        }
        
    	$ventes = $this->getDoctrine()
                ->getRepository('AppBundle:Approvisionnement')
                ->entreesSorties(
                    0, 
                    2,
                    $this->getParams($params, 'annee'),
                    $id_entrepot,
                    $agence->getId(),
                    $this->getParams($params, 'ajourdhui'),
                    $this->getParams($params, 'mois')
                );

        if (!empty($ventes)) {
        	return array_sum( array_column($ventes, 'total') );
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
