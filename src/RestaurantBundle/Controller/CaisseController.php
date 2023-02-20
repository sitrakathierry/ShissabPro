<?php

namespace RestaurantBundle\Controller;

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

        return $this->render('RestaurantBundle:Caisse:index.html.twig', array(
            'agence' => $agence,
            'agences' => $agences,
            'userAgence' => $userAgence,
        ));
    }

    public function payerAction(Request $request)
    {

        $id = $request->request->get('id');
        $montant_remise = $request->request->get('montant_remise');
        $montant_a_payer = $request->request->get('montant_a_payer');
        $montant_total = $request->request->get('montant_total');
        $montant_recu = $request->request->get('montant_recu');
        $montant_a_rendre = $request->request->get('montant_a_rendre');

        $reservation = $this->getDoctrine()
                ->getRepository('AppBundle:Reservation')
                ->find($id);

        $reservation->setRemise($montant_remise);
        $reservation->setMontantRecu($montant_recu);
        $reservation->setMontantRendu($montant_a_rendre);
        $reservation->setStatut(3);

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
    }
}
