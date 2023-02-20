<?php

namespace RestaurantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CreditController extends Controller
{
    public function validerAction($id, $type = 'reservation')
    {

    	if ($type == 'reservation') {
	        $reservation = $this->getDoctrine()
	                ->getRepository('AppBundle:Reservation')
	                ->find($id);

	        $reservation->setStatut(20);

	        $em = $this->getDoctrine()->getManager();
	        $em->persist($reservation);
	        $em->flush();

	        $selected_tables = json_decode($reservation->getSelectedTables());

	        foreach ($selected_tables as $item) {
	            $id_table = $item->id;
	            $assis = $item->assis;

	            $table = $this->getDoctrine()
	                ->getRepository('AppBundle:TableRestaurant')
	                ->find($id_table);

	            $table->setDisponibilite(1);
	            $table->setDisponible( $table->getDisponible() + $assis );

	            $em->persist($table);
	            $em->flush();
	        }

	        return new JsonResponse(array(
	            'id' => $reservation->getId()
	        ));
    	} else {
    		$emporter = $this->getDoctrine()
                ->getRepository('AppBundle:Emporter')
                ->find($id);

	        $emporter->setStatut(20);

	        $em = $this->getDoctrine()->getManager();
	        $em->persist($emporter);
	        $em->flush();

	        return new JsonResponse(array(
	            'id' => $emporter->getId()
	        ));
    	}

    }
}
