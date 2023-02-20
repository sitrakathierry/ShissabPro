<?php

namespace HebergementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CronController extends Controller
{
	public function annulationAction()
	{
		$bookings = $this->getDoctrine()
                    ->getRepository('AppBundle:Booking')
                    ->nonConfirmes();


        foreach ($bookings as $booking) {
        	$date = new \DateTime($booking['date']);
        	$hours = $booking['annulation_automatique'];

        	if ($hours) {

	        	$date->add(new \DateInterval('PT' . $hours . 'H'));
	            $now = new \DateTime();
	            
	            if ($date->format('Y-m-d H:m:i') < $now->format('Y-m-d H:m:i')) {


	            	$b = $this->getDoctrine()
		                ->getRepository('AppBundle:Booking')
		                ->find($booking['id']);

		            $b->setStatut(5);

		            $em = $this->getDoctrine()->getManager();
			        $em->persist($b);
			        $em->flush();
	            }
        	}


        }

        return new Response(1);
	}
}
