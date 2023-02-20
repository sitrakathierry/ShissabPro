<?php

namespace ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
	public function indexAction()
    {
    	$total = $this->totalClients();
    	$morales = $this->totalClients(1);
    	$physiques = $this->totalClients(2);

        $data = array(
            array(
                'label' => 'PERS. MORAL',
                'data' => $morales,
                'color' => '#f8ac59',
            ),
            array(
                'label' => 'PERS. PHYSIQUE',
                'data' => $physiques,
                'color' => '#23c6c8',
            )
        );

        return $this->render('ClientBundle:Dashboard:index.html.twig',array(
        	'total' => $total,
        	'morales' => $morales,
        	'physiques' => $physiques,
            'data' => json_encode($data),
        ));
    }

    public function totalClients($statut = 0)
    {

    	$user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $agence = $userAgence->getAgence();

    	$clients = $this->getDoctrine()
                                ->getRepository('AppBundle:Client')
                                ->liste(
                                	$statut,
                                	$agence->getId()
                                );

        return count($clients);
    }

}
