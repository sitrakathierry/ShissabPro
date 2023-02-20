<?php

namespace RestaurantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Reservation;
use AppBundle\Entity\ReservationDetails;
use HebergementBundle\Controller\BaseController;

class ReservationController extends BaseController
{
	public function indexAction()
    {
        return $this->render('RestaurantBundle:Reservation:index.html.twig');
    }

    public function addAction()
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        $plats = $this->getDoctrine()
                ->getRepository('AppBundle:Plat')
                ->findBy(array(
                    'agence' => $agence,
                    'statut' => 1,
                ));

        $bookings = $this->getDoctrine()
                ->getRepository('AppBundle:Booking')
                ->getList(
                    $agence->getId(),
                    '',
                    '',
                    0,
                    2
                );

        $hebergementRestaurantRelation = $this->hebergementRestaurantRelation();

        $accompagnements = $this->getDoctrine()
                ->getRepository('AppBundle:Accompagnement')
                ->findBy(array(
                    'agence' => $agence,
                ));

        return $this->render('RestaurantBundle:Reservation:add.html.twig',array(
            'agence' => $agence,
            'plats' => $plats,
            'bookings' => $bookings,
            'hebergementRestaurantRelation' => $hebergementRestaurantRelation,
            'accompagnements' => $accompagnements,
        ));
    }

    public function saveAction(Request $request)
    {
        $id = $request->request->get('id');
        $booking = $request->request->get('booking');
        $nb_place = $request->request->get('nb_place');
        $selected_tables = $request->request->get('selected_tables');
        $montant_total = $request->request->get('montant_total');
        $statut = $request->request->get('statut');
        $date = $request->request->get('date');

        // $date = new \DateTime('now');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        if ($id) {
            $reservation = $this->getDoctrine()
                ->getRepository('AppBundle:Reservation')
                ->find($id);
        } else {
            $reservation = new Reservation();
        }

        $reservation->setNbPlace($nb_place);
        $reservation->setSelectedTables( json_encode( $selected_tables ) );
        $reservation->setTotal($montant_total);
        $reservation->setStatut($statut);
        $reservation->setAgence($agence);

        if ($date) {
            $date = \DateTime::createFromFormat('j/m/Y', $date);
            $reservation->setDate($date);
        }

        if ($booking) {
            $booking = $this->getDoctrine()
                ->getRepository('AppBundle:Booking')
                ->find($booking);
                
            $reservation->setBooking($booking);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($reservation);
        $em->flush();

        if (!$id) {
            foreach ($selected_tables as $selected) {
                $id_table = $selected['id'];
                $assis = $selected['assis'];

                $table = $this->getDoctrine()
                    ->getRepository('AppBundle:TableRestaurant')
                    ->find($id_table);

                $place = $table->getPlace();
                $disponible = $table->getDisponible();
                $occupe = $place - $disponible ;

                $disponible -= $assis;

                $occupe += $assis;

                if ($place == $occupe) {
                    $table->setDisponibilite(0);
                }

                $table->setDisponible($disponible);

                $em->persist($table);
                $em->flush();
            }
        }


        $details = $this->getDoctrine()
                ->getRepository('AppBundle:ReservationDetails')
                ->findBy(array(
                    'reservation' => $reservation
                ));

        foreach ($details as $detail) {
            $em->remove($detail);
            $em->flush();
        }

        $tables = $request->request->get('tables');
        $plats = $request->request->get('plat');
        $qte = $request->request->get('qte');
        $prix = $request->request->get('prix');
        $total = $request->request->get('total');
        $cuisson = $request->request->get('cuisson');
        $statut_details = $request->request->get('statut_detail');
        $accompagnement_details = $request->request->get('accompagnement_details');

        if (!empty($tables)) {
            foreach ($tables as $key => $value) {
                $detail = new ReservationDetails();

                $tables_detail = json_encode( $tables[$key] );
                $plat_detail = $plats[$key];
                $qte_detail = $qte[$key];
                $prix_detail = $prix[$key];
                $total_detail = $total[$key];
                $cuisson_detail = $cuisson[$key];
                $statut_detail = $statut_details[$key];
                $accompagnements = $accompagnement_details[$key];

                $detail->setTables($tables_detail);

                $plat = $this->getDoctrine()
                        ->getRepository('AppBundle:Plat')
                        ->find( $plat_detail );

                $detail->setPlat($plat);
                $detail->setQte($qte_detail);
                $detail->setPrix($prix_detail);
                $detail->setTotal($total_detail);
                $detail->setCuisson($cuisson_detail);
                $detail->setStatut($statut_detail);
                $detail->setReservation($reservation);
                $detail->setAccompagnements( json_encode( $accompagnements ) );

                $em->persist($detail);
                $em->flush();

            }
        }

        return new JsonResponse(array(
            'id' => $reservation->getId()
        ));

    }

    public function encoursAction()
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

        return $this->render('RestaurantBundle:Reservation:encours.html.twig', array(
            'agences' => $agences,
            'userAgence' => $userAgence,
        ));
    }

    public function listAction(Request $request)
    {

        $agence = $request->request->get('agence');
        $statut = $request->request->get('statut');
        $cuisine = $request->request->get('cuisine');
        $caisse = $request->request->get('caisse');
        $termines = $request->request->get('termines');
        $ficheconsommation = $request->request->get('ficheconsommation');
        $type = $request->request->get('type');

        $type_date = $request->request->get('type_date');
        $mois = $request->request->get('mois');
        $annee = $request->request->get('annee');
        $date_specifique = $request->request->get('date_specifique');
        $debut_date = $request->request->get('debut_date');
        $fin_date = $request->request->get('fin_date');

        $commande_type = $request->request->get('commande_type');

        $reservations = $this->getDoctrine()
                        ->getRepository('AppBundle:Reservation')
                        ->consultation(
                            $agence,
                            $statut,
                            0,
                            $ficheconsommation,
                            0,
                            $type_date, 
                            $mois, 
                            $annee, 
                            $date_specifique, 
                            $debut_date, 
                            $fin_date
                        );

        $agence = $this->getDoctrine()
                ->getRepository('AppBundle:Agence')
                ->find($agence);


        $data = array();

        $accompagnements = $this->getDoctrine()
                ->getRepository('AppBundle:Accompagnement')
                ->findBy(array(
                    'agence' => $agence,
                ));


        if ($commande_type != 2) {
            foreach ($reservations as $reservation) {
                $details = $this->getDoctrine()
                        ->getRepository('AppBundle:ReservationDetails')
                        ->consultation($reservation['id'], $type);


                $reservation['details'] = null;

                if (!empty($details)) {
                    $reservation['details'] = $details;
                }

                array_push($data, $reservation);
            }
        }

        if ($cuisine == 1 || $caisse == 1 || $termines == 1 || $ficheconsommation == 1) {

            $results = array();

            if ($commande_type != 1) {
                $emporters = $this->getDoctrine()
                            ->getRepository('AppBundle:Emporter')
                            ->consultation(
                                $agence->getId(),
                                $statut,
                                0,
                                $ficheconsommation,
                                0,
                                $type_date, 
                                $mois, 
                                $annee, 
                                $date_specifique, 
                                $debut_date, 
                                $fin_date
                            );

                foreach ($emporters as $emporter) {
                    $details = $this->getDoctrine()
                            ->getRepository('AppBundle:EmporterDetails')
                            ->consultation($emporter['id'], $type);


                    $emporter['details'] = null;

                    if (!empty($details)) {
                        $emporter['details'] = $details;
                    }

                    array_push($results, $emporter);
                }
            }


            $commandes = array_merge($data, $results);

            usort($commandes, function($a,$b){
                return \DateTime::createFromFormat('j/m/Y', $b['date']) <=> \DateTime::createFromFormat('j/m/Y', $a['date']);
            });

            if ($cuisine == 1) {

                if ($type == 2) {
                    return $this->render('RestaurantBundle:Cuisine:list-boisson.html.twig',array(
                        'agence' => $agence,
                        'commandes' => $commandes,
                        'accompagnements' => $accompagnements,
                    ));
                }

                return $this->render('RestaurantBundle:Cuisine:list-plat.html.twig',array(
                    'agence' => $agence,
                    'commandes' => $commandes,
                    'accompagnements' => $accompagnements,
                ));

            }

            if ($caisse == 1){
                return $this->render('RestaurantBundle:Caisse:list.html.twig',array(
                    'agence' => $agence,
                    'commandes' => $commandes,
                    'accompagnements' => $accompagnements,
                ));
            }

            if ($termines == 1 || $ficheconsommation == 1){
                return $this->render('RestaurantBundle:Termines:list.html.twig',array(
                    'agence' => $agence,
                    'commandes' => $commandes,
                    'accompagnements' => $accompagnements,
                ));
            }
            
        }


        return $this->render('RestaurantBundle:Reservation:list.html.twig',array(
            'agence' => $agence,
            'reservations' => $data,
            'caisse' => $caisse,
            'accompagnements' => $accompagnements,
        ));
        
    }

    public function showAction($id)
    {

        $reservation = $this->getDoctrine()
                ->getRepository('AppBundle:Reservation')
                ->find($id);

        $details = $this->getDoctrine()
                ->getRepository('AppBundle:ReservationDetails')
                ->findBy(array(
                    'reservation' => $reservation,
                ));

        foreach ($details as $detail) {
            $accs = $this->getDoctrine()
                ->getRepository('AppBundle:ReservationDetails')
                ->accompagnementsDetails($detail->getAccompagnements());

            $detail->setAccompagnementsList($accs['accompagnements']);
            $detail->setTotalAccompagnement($accs['total_accompagnement']);
        }
                    
        $agence = $reservation->getAgence();

        $plats = $this->getDoctrine()
                ->getRepository('AppBundle:Plat')
                ->findBy(array(
                    'agence' => $agence,
                    'statut' => 1,
                ));

        $selected = $reservation->getSelectedTables();

        $selected_tables = json_decode($selected);

        $tables = [];

        foreach ($selected_tables as $value) {
            $table = $this->getDoctrine()
                ->getRepository('AppBundle:TableRestaurant')
                ->find($value->id);

            $item = array(
                'id' => $table->getId(),
                'nom' => $table->getNom(),
                'disponible' => $table->getDisponible(),
                'place' => $table->getPlace(),
                'assis' => $value->assis,
            );

            array_push($tables, $item);
        }

        $accompagnements = $this->getDoctrine()
                ->getRepository('AppBundle:Accompagnement')
                ->findBy(array(
                    'agence' => $agence,
                ));

        return $this->render('RestaurantBundle:Reservation:show.html.twig',array(
            'agence' => $agence,
            'reservation' => $reservation,
            'plats' => $plats,
            'tables' => $tables,
            'details' => $details,
            'accompagnements' => $accompagnements,
        ));
    }

    public function annulerDetailsAction($id)
    {
        $detail = $this->getDoctrine()
                ->getRepository('AppBundle:ReservationDetails')
                ->find($id);

        $montant = $detail->getTotal();

        $em = $this->getDoctrine()->getManager();
        $em->remove($detail);
        $em->flush();

        $reservation = $detail->getReservation();

        $reservation->setTotal( $reservation->getTotal() - $montant );

        $em->persist($reservation);
        $em->flush();

        return new JsonResponse(array(
            'id' => $reservation->getId()
        ));
    }


    public function terminerDetailsAction($id)
    {
        $detail = $this->getDoctrine()
                ->getRepository('AppBundle:ReservationDetails')
                ->find($id);

        $detail->setStatut(2);

        $em = $this->getDoctrine()->getManager();
        $em->persist($detail);
        $em->flush();

        $reservation = $detail->getReservation();

        // $details = $this->getDoctrine()
        //         ->getRepository('AppBundle:ReservationDetails')
        //         ->findBy(array(
        //             'reservation' => $reservation,
        //             'statut' => 1,
        //         ));

        // if (empty($details)) {
        //     $reservation->setStatut(2);
            
        //     $em->persist($reservation);
        //     $em->flush();
        // }

        return new JsonResponse(array(
            'id' => $reservation->getId()
        ));
    }

    public function terminerAction($id)
    {
        $reservation = $this->getDoctrine()
                ->getRepository('AppBundle:Reservation')
                ->find($id);

        $reservation->setStatut(2);

        $em = $this->getDoctrine()->getManager();
        $em->persist($reservation);
        $em->flush();

        $details = $this->getDoctrine()
                ->getRepository('AppBundle:ReservationDetails')
                ->findBy(array(
                    'reservation' => $reservation,
                    'statut' => 1,
                ));

        foreach ($details as $detail) {
            $detail->setStatut(2);

            $em->persist($reservation);
            $em->flush();
        }

        return new JsonResponse(array(
            'id' => $reservation->getId()
        ));
    }

    public function terminesAction()
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

        return $this->render('RestaurantBundle:Reservation:termines.html.twig', array(
            'agences' => $agences,
            'userAgence' => $userAgence,
        ));
    }

    public function ticketAction(Request $request)
    {
        $id = $request->request->get('id');
        $printer_name = $request->request->get('printer_name');

        $reservation = $this->getDoctrine()
                ->getRepository('AppBundle:Reservation')
                ->find($id);

        $data = $this->makeTicketData($reservation);

        return new JsonResponse(array(
            'data' => $data
        ));

    }

    public function printSelectedTables($reservation)
    {
        $selected = $reservation->getSelectedTables();
        $selected_tables = json_decode($selected);

        $tables = [];

        foreach ($selected_tables as $value) {
            $table = $this->getDoctrine()
                ->getRepository('AppBundle:TableRestaurant')
                ->find($value->id);

            array_push($tables, $table->getNom());
        }

        return implode(" ,", $tables);
    }

    public function makeTicketData($reservation)
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $details = $this->getDoctrine()
                ->getRepository('AppBundle:ReservationDetails')
                ->findBy(array(
                    'reservation' => $reservation,
                ));

        foreach ($details as $detail) {
            $accs = $this->getDoctrine()
                ->getRepository('AppBundle:ReservationDetails')
                ->accompagnementsDetails($detail->getAccompagnements());

            $detail->setAccompagnementsList($accs['accompagnements']);
            $detail->setTotalAccompagnement($accs['total_accompagnement']);
        }

        $data = array(
            'agence' => $agence->getTitreTicket(),
            'description' => $agence->getSoustitreTicket(),
            'adresse' => $agence->getAdresseTicket(),
            'tel' => $agence->getTelTicket(),
            'recu' => "Commande N° " . $reservation->getNum(),
            'type' => "Table : " . $this->printSelectedTables( $reservation ),
            'qrcode' => $reservation->getNum(),
            'date' => "Le ". $reservation->getDate()->format('d/m/Y'),
            'thead' => ["Designation","Qte","Total"],
            'tbody' => [],
            'tfoot' => ["Total","",$reservation->getTotal()],
            'montant_recu' => ["Montant reçu","",$reservation->getMontantRecu() ? $reservation->getMontantRecu() : "0"],
            'montant_rendu' => ["Montant rendu","",$reservation->getMontantRendu() ? $reservation->getMontantRendu() : "0"],
            'caissier' => [ "Caissier :","",$user->getUsername() ],
            'statut' => [ "Statut :" ,"",( $reservation->getStatut() == 2 ) ? "Non Payé" : "Payé" ],
        );

        foreach ($details as $detail) {

            $data['tbody'] = $this->addRow( $data['tbody'],array(
                'designation' => $detail->getPlat()->getNom(),
                'qte' => $detail->getQte(), 
                'total' => $detail->getPrix() * $detail->getQte()
            ) );

            // accompagnements()
            $accompagnements = $detail->getAccompagnementsList();
            if (!empty($accompagnements)) {
                foreach ($accompagnements as $accDetail) {

                    $accompagnement = $this->getDoctrine()
                    ->getRepository('AppBundle:Accompagnement')
                    ->find($accDetail['accompagnement']);

                    if ($accompagnement) {
                        if ( strval($accDetail['prix_accompagnement']) > 0) {
                            $data['tbody'] = $this->addRow( $data['tbody'], array(
                                'designation' => $accompagnement->getNom(), 
                                'qte' => $accDetail['qte_accompagnement'], 
                                'total' => $accDetail['prix_accompagnement']
                            ) ); 
                        }
                    }

                    
                }
            }
        }

        return $data;
    }

    public function addRow($tbody, $item)
    {


        $designation = $item['designation'];

        if (strlen($designation) > 13) {
            $designation = substr($designation, 0, 12) . " ---";
        }

        $tr = [ $designation , " " . $item['qte'] ,  $item['total'] ];
        array_push($tbody, $tr);

        return $tbody;

        // $designation = str_split($item['designation'], 16);
        
        // if (count($designation) > 1) {
        //     for ($i=0; $i < count($designation); $i++) { 
        //         $value = $designation[$i];
        //         if ($i == count($designation) - 1) {
        //             $tr = [ $designation[$i] , " " . $item['qte'] ,  $item['total'] ];
        //             array_push($tbody, $tr);
        //         } else {
        //             array_push($tbody, [$value . " "]);
        //         }
        //     }
        // } else {
        //     $tr = [ $designation[0] , " " . $item['qte'] ,  $item['total'] ];
        //     array_push($tbody, $tr);
        // }

        // return $tbody;

    }

    public function deleteAction($id)
    {
        $reservation  = $this->getDoctrine()
                        ->getRepository('AppBundle:Reservation')
                        ->find($id);

        $selected = $reservation->getSelectedTables();

        $selected_tables = json_decode($selected);

        $em = $this->getDoctrine()->getManager();

        foreach ($selected_tables as $selected) {

            $id_table = $selected->id;
            $assis = $selected->assis;

            $table = $this->getDoctrine()
                ->getRepository('AppBundle:TableRestaurant')
                ->find($id_table);

            $table->setDisponibilite(1);
            $table->setDisponible( $table->getDisponible() + intval($assis) );
            
            $em->persist($table);
            $em->flush();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($reservation);
        $em->flush();

        return new JsonResponse(200);
        
    }
}
