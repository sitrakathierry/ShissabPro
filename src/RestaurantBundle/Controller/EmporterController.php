<?php

namespace RestaurantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Emporter;
use AppBundle\Entity\EmporterDetails;
use HebergementBundle\Controller\BaseController;

class EmporterController extends BaseController
{
    public function indexAction()
    {
        return $this->render('RestaurantBundle:Emporter:index.html.twig');
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

        $accompagnements = $this->getDoctrine()
                ->getRepository('AppBundle:Accompagnement')
                ->findBy(array(
                    'agence' => $agence,
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

        return $this->render('RestaurantBundle:Emporter:add.html.twig',array(
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
        $montant_total = $request->request->get('montant_total');
        $statut = $request->request->get('statut');
        $booking = $request->request->get('booking');
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
            $emporter = $this->getDoctrine()
                ->getRepository('AppBundle:Emporter')
                ->find($id);
        } else {
            $emporter = new Emporter();
        }

        $emporter->setTotal($montant_total);
        $emporter->setStatut($statut);
        $emporter->setAgence($agence);

        if ($date) {
            $date = \DateTime::createFromFormat('j/m/Y', $date);
            $emporter->setDate($date);
        }

        if ($booking) {
            $booking = $this->getDoctrine()
                ->getRepository('AppBundle:Booking')
                ->find($booking);
                
            $emporter->setBooking($booking);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($emporter);
        $em->flush();

        $details = $this->getDoctrine()
                ->getRepository('AppBundle:EmporterDetails')
                ->findBy(array(
                    'emporter' => $emporter
                ));

        foreach ($details as $detail) {
            $em->remove($detail);
            $em->flush();
        }

        $plats = $request->request->get('plat');
        $qte = $request->request->get('qte');
        $prix = $request->request->get('prix');
        $total = $request->request->get('total');
        $cuisson = $request->request->get('cuisson');
        $statut_details = $request->request->get('statut_detail');
        $accompagnement_details = $request->request->get('accompagnement_details');

        if (!empty($plats)) {
            foreach ($plats as $key => $value) {
                $detail = new EmporterDetails();

                $plat_detail = $plats[$key];
                $qte_detail = $qte[$key];
                $prix_detail = $prix[$key];
                $total_detail = $total[$key];
                $cuisson_detail = $cuisson[$key];
                $statut_detail = $statut_details[$key];
                $accompagnements = $accompagnement_details[$key];

                $plat = $this->getDoctrine()
                        ->getRepository('AppBundle:Plat')
                        ->find( $plat_detail );

                $detail->setPlat($plat);
                $detail->setQte($qte_detail);
                $detail->setPrix($prix_detail);
                $detail->setTotal($total_detail);
                $detail->setCuisson($cuisson_detail);
                $detail->setStatut($statut_detail);
                $detail->setEmporter($emporter);
                $detail->setAccompagnements( json_encode( $accompagnements ) );

                $em->persist($detail);
                $em->flush();

            }
        }

        return new JsonResponse(array(
            'id' => $emporter->getId()
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

        return $this->render('RestaurantBundle:Emporter:encours.html.twig', array(
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

        $emporters = $this->getDoctrine()
                        ->getRepository('AppBundle:Emporter')
                        ->consultation(
                            $agence,
                            $statut
                        );

        $data = array();

        foreach ($emporters as $emporter) {
            $details = $this->getDoctrine()
                    ->getRepository('AppBundle:EmporterDetails')
                    ->consultation($emporter['id']);


            $emporter['details'] = null;

            if (!empty($details)) {
                $emporter['details'] = $details;
            }

            array_push($data, $emporter);
        }

        // if ($cuisine == 1) {
        //     return $this->render('RestaurantBundle:Cuisine:list.html.twig',array(
        //         'emporters' => $data
        //     ));
            
        // }

        $agence = $this->getDoctrine()
                    ->getRepository('AppBundle:Agence')
                    ->find($agence);

        $accompagnements = $this->getDoctrine()
                ->getRepository('AppBundle:Accompagnement')
                ->findBy(array(
                    'agence' => $agence,
                ));

        return $this->render('RestaurantBundle:Emporter:list.html.twig',array(
            'agence' => $agence,
            'emporters' => $data,
            'caisse' => $caisse,
            'accompagnements' => $accompagnements,
        ));
        
    }

    public function showAction($id)
    {

        $emporter = $this->getDoctrine()
                ->getRepository('AppBundle:Emporter')
                ->find($id);

        $details = $this->getDoctrine()
                ->getRepository('AppBundle:EmporterDetails')
                ->findBy(array(
                    'emporter' => $emporter,
                ));


        foreach ($details as $detail) {
            $accs = $this->getDoctrine()
                ->getRepository('AppBundle:EmporterDetails')
                ->accompagnementsDetails($detail->getAccompagnements());

            $detail->setAccompagnementsList($accs['accompagnements']);
            $detail->setTotalAccompagnement($accs['total_accompagnement']);
        }
                    
        $agence = $emporter->getAgence();

        $plats = $this->getDoctrine()
                ->getRepository('AppBundle:Plat')
                ->findBy(array(
                    'agence' => $agence,
                    'statut' => 1,
                ));

        $accompagnements = $this->getDoctrine()
                ->getRepository('AppBundle:Accompagnement')
                ->findBy(array(
                    'agence' => $agence,
                ));

        return $this->render('RestaurantBundle:Emporter:show.html.twig',array(
            'agence' => $agence,
            'emporter' => $emporter,
            'plats' => $plats,
            'details' => $details,
            'accompagnements' => $accompagnements,
        ));
    }

    public function terminerDetailsAction($id)
    {
        $detail = $this->getDoctrine()
                ->getRepository('AppBundle:EmporterDetails')
                ->find($id);

        $detail->setStatut(2);

        $em = $this->getDoctrine()->getManager();
        $em->persist($detail);
        $em->flush();

        $emporter = $detail->getEmporter();

        return new JsonResponse(array(
            'id' => $emporter->getId()
        ));
    }

    public function annulerDetailsAction($id)
    {
        $detail = $this->getDoctrine()
                ->getRepository('AppBundle:EmporterDetails')
                ->find($id);

        $montant = $detail->getTotal();

        $em = $this->getDoctrine()->getManager();
        $em->remove($detail);
        $em->flush();

        $emporter = $detail->getEmporter();

        $emporter->setTotal( $emporter->getTotal() - $montant );

        $em->persist($emporter);
        $em->flush();

        return new JsonResponse(array(
            'id' => $emporter->getId()
        ));
    }

    public function terminerAction($id)
    {
        $emporter = $this->getDoctrine()
                ->getRepository('AppBundle:Emporter')
                ->find($id);

        $emporter->setStatut(2);

        $em = $this->getDoctrine()->getManager();
        $em->persist($emporter);
        $em->flush();

        $details = $this->getDoctrine()
                ->getRepository('AppBundle:EmporterDetails')
                ->findBy(array(
                    'emporter' => $emporter,
                    'statut' => 1,
                ));

        foreach ($details as $detail) {
            $detail->setStatut(2);

            $em->persist($emporter);
            $em->flush();
        }

        return new JsonResponse(array(
            'id' => $emporter->getId()
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

        $emporter = $this->getDoctrine()
                ->getRepository('AppBundle:Emporter')
                ->find($id);

        $emporter->setRemise($montant_remise);
        $emporter->setMontantRecu($montant_recu);
        $emporter->setMontantRendu($montant_a_rendre);
        $emporter->setStatut(3);

        $em = $this->getDoctrine()->getManager();
        $em->persist($emporter);
        $em->flush();

        return new JsonResponse(array(
            'id' => $emporter->getId()
        ));
    }

    public function ticketAction(Request $request)
    {
        $id = $request->request->get('id');
        $printer_name = $request->request->get('printer_name');

        $emporter = $this->getDoctrine()
                ->getRepository('AppBundle:Emporter')
                ->find($id);

        $data = $this->makeTicketData($emporter);

        return new JsonResponse(array(
            'data' => $data
        ));

    }

    public function makeTicketData($emporter)
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $details = $this->getDoctrine()
                ->getRepository('AppBundle:EmporterDetails')
                ->findBy(array(
                    'emporter' => $emporter,
                ));

        foreach ($details as $detail) {
            $accs = $this->getDoctrine()
                ->getRepository('AppBundle:EmporterDetails')
                ->accompagnementsDetails($detail->getAccompagnements());

            $detail->setAccompagnementsList($accs['accompagnements']);
            $detail->setTotalAccompagnement($accs['total_accompagnement']);
        }

        $data = array(
            'agence' => $agence->getNom(),
            'description' => $agence->getSoustitreTicket(),
            'adresse' => $agence->getAdresseTicket(),
            'tel' => $agence->getTelTicket(),
            'recu' => "Commande N° " . $emporter->getNum(),
            'type' => "À Emporter",
            'qrcode' => $emporter->getNum(),
            'date' => "Le ". $emporter->getDate()->format('d/m/Y'),
            'thead' => ["Designation","Qte","Total"],
            'tbody' => [],
            'tfoot' => ["Total","",$emporter->getTotal()],
            'montant_recu' => ["Montant reçu","",$emporter->getMontantRecu() ? $emporter->getMontantRecu() : "0"],
            'montant_rendu' => ["Montant rendu","",$emporter->getMontantRendu() ? $emporter->getMontantRendu() : "0"],
            'caissier' => [ "Caissier :",$user->getUsername() ],
            'statut' => [ "Statut :" ,"",( $emporter->getStatut() == 2 ) ? "Non Payé" : "Payé" ],
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
                        if (strval($accDetail['prix_accompagnement']) > 0) {
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
        $designation = str_split($item['designation'], 16);
        
        if (count($designation) > 1) {
            for ($i=0; $i < count($designation); $i++) { 
                $value = $designation[$i];
                if ($i == count($designation) - 1) {
                    $tr = [ $designation[$i] , " " . $item['qte'] ,  $item['total'] ];
                    array_push($tbody, $tr);
                } else {
                    array_push($tbody, [$value . " "]);
                }
            }
        } else {
            $tr = [ $designation[0] , " " . $item['qte'] ,  $item['total'] ];
            array_push($tbody, $tr);
        }

        return $tbody;

    }

    public function deleteAction($id)
    {
        $emporter  = $this->getDoctrine()
                        ->getRepository('AppBundle:Emporter')
                        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($emporter);
        $em->flush();

        return new JsonResponse(200);
        
    }

}
