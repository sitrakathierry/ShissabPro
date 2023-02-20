<?php

namespace RestaurantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class NotificationController extends Controller
{

	public function notificationAction(Request $request)
	{
		$type_reponse = $request->request->get('type_reponse');

		$notif_reserv = $this->getDoctrine()
                    ->getRepository('AppBundle:Reservation')
                    ->notificationsReservations();

        $notif_emp   = $this->getDoctrine()
                    ->getRepository('AppBundle:Emporter')            
                    ->notificationsEmporters();

        $user = $this->getUser();
        // $check_menu = $this->get('app.check_menu');
        // $checkResto = $check_menu->check(['restaurant_homepage'],$user,$this->getDoctrine());

        if ($type_reponse == 'html') {
	        return $this->render('RestaurantBundle:Notification:index.html.twig',array(
	        	'notif_reserv' => $notif_reserv,
	        	'notif_emp'	   => $notif_emp,
                                'menu'         => "",
	        ));
        } else {
        	return new JsonResponse($notif_reserv);
        }
	}

}