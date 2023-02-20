<?php

namespace HebergementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CaisseController extends Controller
{
	public function indexAction()
    {
        $agences  = $this->getDoctrine()
                        ->getRepository('AppBundle:Agence')
                        ->findAll();

        $user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $agence = $userAgence->getAgence();

        $categories = $this->getDoctrine()
                    ->getRepository('AppBundle:CategorieChambre')
                    ->findBy(array(
                        'agence' => $agence
                    ));

        $chambres = $this->getDoctrine()
                    ->getRepository('AppBundle:Chambre')
                    ->findBy(array(
                        'agence' => $agence
                    ));

        return $this->render('HebergementBundle:Caisse:index.html.twig', array(
            'agences' => $agences,
            'chambres' => $chambres,
            'categories' => $categories,
            'userAgence' => $userAgence,
        ));
    }

    public function cashAction($id)
    {
        $booking  = $this->getDoctrine()
                        ->getRepository('AppBundle:Booking')
                        ->find($id);

        $totalReservation = $this->getDoctrine()
                        ->getRepository('AppBundle:Booking')
                        ->totalReservation($id);

        $totalEmporter = $this->getDoctrine()
                        ->getRepository('AppBundle:Booking')
                        ->totalEmporter($id);

        $totalConsommation = $totalEmporter + $totalReservation;

        $agence = $booking->getAgence();

        return $this->render('HebergementBundle:Caisse:cash.html.twig', array(
            'agence' => $agence,
            'booking' => $booking,
            'totalConsommation' => $totalConsommation,
        ));
    }

    public function payerAction(Request $request)
    {

        $id = $request->request->get('id');
        $montant_remise = $request->request->get('montant_remise');
        $type_remise = $request->request->get('type_remise');
        $montant_a_payer = $request->request->get('montant_a_payer');
        $montant_total = $request->request->get('montant_total');

        $booking  = $this->getDoctrine()
            ->getRepository('AppBundle:Booking')
            ->find($id);

        $booking->setStatut(4);
        $booking->setRemise($montant_remise);
        $booking->setTypeRemise($type_remise);

        $em = $this->getDoctrine()->getManager();
        $em->persist($booking);
        $em->flush();

        $reservations  = $this->getDoctrine()
            ->getRepository('AppBundle:Reservation')
            ->findBy(array(
                'booking' => $booking
            ));

        foreach ($reservations as $reservation) {
            $reservation->setStatut(3);
            $em->persist($reservation);
            $em->flush();
        }

        $emporters  = $this->getDoctrine()
            ->getRepository('AppBundle:Emporter')
            ->findBy(array(
                'booking' => $booking
            ));

        foreach ($emporters as $emporter) {
            $emporter->setStatut(3);
            $em->persist($emporter);
            $em->flush();
        }

        return new JsonResponse(array(
            'id' => $booking->getId()
        ));
    }
}
