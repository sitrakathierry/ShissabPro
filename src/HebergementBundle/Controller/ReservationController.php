<?php

namespace HebergementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use AppBundle\Entity\Booking;
use AppBundle\Entity\RemboursementBooking;
use AppBundle\Entity\AnnulationNuit;
use HebergementBundle\Controller\BaseController;

class ReservationController extends BaseController
{
	public function indexAction()
    {
        return $this->render('HebergementBundle:Reservation:index.html.twig');
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

        $categories = $this->getDoctrine()
                    ->getRepository('AppBundle:CategorieChambre')
                    ->findBy(array(
                        'agence' => $agence,
                    ));

        $clients = $this->getDoctrine()
            ->getRepository('AppBundle:Client')
            ->findBy(array(
                'agence' => $agence
            ));


        return $this->render('HebergementBundle:Reservation:add.html.twig',array(
            'agence' => $agence,
            'categories' => $categories,
            'clients' => $clients,
            'userAgence' => $userAgence,
        ));
    }

    public function saveAction(Request $request)
    {
        $id = $request->request->get('id');
        $nb_pers = $request->request->get('nb_pers');
        $date_entree = $request->request->get('date_entree');
        $date_sortie = $request->request->get('date_sortie');
        $avec_petit_dejeuner = $request->request->get('avec_petit_dejeuner');
        $total = $request->request->get('total');
        $chambre_id = $request->request->get('chambre_id');
        $montant_petit_dejeuner = $request->request->get('montant_petit_dejeuner');
        $reservation_nb_nuit = $request->request->get('reservation_nb_nuit');
        $montant = $request->request->get('montant');
        $statut = $request->request->get('statut');
        $heure_entree = $request->request->get('heure_entree');
        $heure_sortie = $request->request->get('heure_sortie');
        $nom_client = $request->request->get('nom_client');
        $tel_client = $request->request->get('tel_client');
        $client = $request->request->get('client');
        $date = $request->request->get('date');
        $lieu = $request->request->get('lieu');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();


        $date_entree = \DateTime::createFromFormat('j/m/Y', $date_entree);
        $date_sortie = \DateTime::createFromFormat('j/m/Y', $date_sortie);
        $heure_entree = \DateTime::createFromFormat('H:i', $heure_entree);
        $heure_sortie = \DateTime::createFromFormat('H:i', $heure_sortie);

        $chambre = $this->getDoctrine()
                ->getRepository('AppBundle:Chambre')
                ->find($chambre_id);


        if ($id) {
            $booking = $this->getDoctrine()
                ->getRepository('AppBundle:Booking')
                ->find($id);
        } else {
            $booking = new Booking();
        }

        if ($client) {
            $client = $this->getDoctrine()
                ->getRepository('AppBundle:Client')
                ->find($client);

            $booking->setClient($client);
        }

        if ($date) {
            $date = \DateTime::createFromFormat('j/m/Y', $date);

        } else {
            $date = new \DateTime('now');
        }

        $booking->setNbPers($nb_pers);
        $booking->setNbNuit($reservation_nb_nuit);
        $booking->setDate($date);
        $booking->setLieu($lieu);
        $booking->setDateEntree($date_entree);
        $booking->setDateSortie($date_sortie);
        $booking->setHeureEntree($heure_entree);
        $booking->setHeureSortie($heure_sortie);
        $booking->setAvecPetitDejeuner($avec_petit_dejeuner);
        $booking->setTotal($total);
        $booking->setChambre($chambre);
        $booking->setMontantPetitDejeuner($montant_petit_dejeuner);
        $booking->setMontant($montant);
        $booking->setStatut($statut);
        $booking->setNom($nom_client);
        $booking->setTel($tel_client);
        $booking->setAgence($agence);

        $em = $this->getDoctrine()->getManager();
        $em->persist($booking);
        $em->flush();

        return new JsonResponse(array(
            'id' => $booking->getId()
        ));

    }

    public function consultationAction()
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

        return $this->render('HebergementBundle:Reservation:consultation.html.twig', array(
            'agences' => $agences,
            'chambres' => $chambres,
            'categories' => $categories,
            'userAgence' => $userAgence,
        ));
    }

    public function listAction(Request $request)
    {
        $agence = $request->request->get('agence');
        $recherche_par = $request->request->get('recherche_par');
        $a_rechercher = $request->request->get('a_rechercher');
        $categorie = $request->request->get('categorie');
        $statut = $request->request->get('statut');
        $chambre = $request->request->get('chambre');
        $type_date = $request->request->get('type_date');
        $mois = $request->request->get('mois');
        $annee = $request->request->get('annee');
        $date_specifique = $request->request->get('date_specifique');
        $debut_date = $request->request->get('debut_date');
        $fin_date = $request->request->get('fin_date');

        $bookings  = $this->getDoctrine()
                        ->getRepository('AppBundle:Booking')
                        ->getList(
                            $agence,
                            $recherche_par,
                            $a_rechercher,
                            $categorie,
                            $statut,
                            $chambre,
                            $type_date,
                            $mois,
                            $annee,
                            $date_specifique,
                            $debut_date,
                            $fin_date
                        );

        return new JsonResponse($bookings);
    }

    public function showAction($id)
    {

        $booking = $this->getDoctrine()
                ->getRepository('AppBundle:Booking')
                ->find($id);

        $factureHebergement = $this->getDoctrine()
                ->getRepository('AppBundle:FactureHebergement')
                ->findOneBy(array(
                    'booking' => $booking
                ));

        $periode = $this->getDoctrine()
                ->getRepository('AppBundle:Booking')
                ->getPeriode(
                    $booking->getDateEntree()->format('d/m/Y'),
                    $booking->getDateSortie()->format('d/m/Y')
                );

        $agence = $booking->getAgence();

        $categories = $this->getDoctrine()
                    ->getRepository('AppBundle:CategorieChambre')
                    ->findBy(array(
                        'agence' => $agence
                    ));

        $reservations = $this->getDoctrine()
                        ->getRepository('AppBundle:Reservation')
                        ->consultation(
                            $agence->getId(),
                            200,
                            0,
                            1,
                            $booking->getId()
                        );

        $data = array();

        foreach ($reservations as $reservation) {
            $details = $this->getDoctrine()
                    ->getRepository('AppBundle:ReservationDetails')
                    ->consultation($reservation['id']);


            $reservation['details'] = null;

            if (!empty($details)) {
                $reservation['details'] = $details;
            }

            array_push($data, $reservation);
        }

        $emporters = $this->getDoctrine()
                        ->getRepository('AppBundle:Emporter')
                        ->consultation(
                            $agence->getId(),
                            200,
                            0,
                            1,
                            $booking->getId()
                        );
        $results = array();

        foreach ($emporters as $emporter) {
            $details = $this->getDoctrine()
                    ->getRepository('AppBundle:EmporterDetails')
                    ->consultation($emporter['id']);


            $emporter['details'] = null;

            if (!empty($details)) {
                $emporter['details'] = $details;
            }

            array_push($results, $emporter);
        }

        $commandes = array_merge($data, $results);


        $annulations = $this->getDoctrine()
                ->getRepository('AppBundle:AnnulationNuit')
                ->findBy(array(
                    'booking' => $booking
                ));

        $checkAnnulation = $this->checkAnnulation($booking);

        $print = false;

        $pdfs = $this->getDoctrine()
                    ->getRepository('AppBundle:PdfAgence')
                    ->findBy(array(
                        'agence' => $agence,
                        'objet' => 6,
                    ));
        if (!empty($pdfs)) {
            $print = true;
        }

        return $this->render('HebergementBundle:Reservation:show.html.twig',array(
            'agence' => $agence,
            'annulations' => $annulations,
            'booking' => $booking,
            'categories' => $categories,
            'commandes' => $commandes,
            'checkAnnulation' => true,
            'print' => $print,
            'periode' => $periode,
            'factureHebergement' => $factureHebergement,
        ));
    }

    public function checkAnnulation($booking)
    {

        $periode_annulation = $booking->getChambre()->getPeriodeAnnulation();
        $date = new \DateTime(date("Y-m-d H:i:s", strtotime('+'. $periode_annulation .' hours')));
        $date_entree = $booking->getDateEntree();

        return $date->format('Y-m-d') < $date_entree->format('Y-m-d');
    }

    public function startAction($id)
    {
        $booking = $this->getDoctrine()
                ->getRepository('AppBundle:Booking')
                ->find($id);

        $booking->setStatut(2);

        $em = $this->getDoctrine()->getManager();
        $em->persist($booking);
        $em->flush();

        return new JsonResponse(array(
            'id' => $booking->getId()
        ));
    }

    public function terminerAction($id)
    {
        $booking = $this->getDoctrine()
                ->getRepository('AppBundle:Booking')
                ->find($id);

        $booking->setStatut(3);

        $em = $this->getDoctrine()->getManager();
        $em->persist($booking);
        $em->flush();

        return new JsonResponse(array(
            'id' => $booking->getId()
        ));
    }

    public function confirmerAction($id)
    {
        $booking = $this->getDoctrine()
                ->getRepository('AppBundle:Booking')
                ->find($id);

        $booking->setStatut(1);

        $em = $this->getDoctrine()->getManager();
        $em->persist($booking);
        $em->flush();

        return new JsonResponse(array(
            'id' => $booking->getId()
        ));
    }

    public function pdfAction($id)
    {
        $booking  = $this->getDoctrine()
                        ->getRepository('AppBundle:Booking')
                        ->find($id);

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $condition = $this->getDoctrine()
                    ->getRepository('AppBundle:ConditionHebergement')
                    ->findOneBy(array(
                        'agence' => $agence
                    ));

            
        $modelePdf = $booking->getModelePdf();      


        $template = $this->renderView('HebergementBundle:Reservation:pdf.html.twig', array(
            'booking' => $booking,
            'condition' => $condition,
            'modelePdf' => $modelePdf,
        ));

        $html2pdf = $this->get('app.html2pdf');

        $html2pdf->create();

        return $html2pdf->generatePdf($template, "booking" . $booking->getId());

    }

    public function annulerAction(Request $request)
    {
        $id = $request->request->get('id');
        $pourcentage_remboursement = $request->request->get('pourcentage_remboursement');
        $montant_remboursement = $request->request->get('montant_remboursement');

        $remboursementBooking = new RemboursementBooking();

        $remboursementBooking->setPourcentage($pourcentage_remboursement);
        $remboursementBooking->setMontant($montant_remboursement);

        $em = $this->getDoctrine()->getManager();
        $em->persist($remboursementBooking);
        $em->flush();

        $booking = $this->getDoctrine()
                ->getRepository('AppBundle:Booking')
                ->find($id);

        $booking->setStatut(5);
        $booking->setRemboursementBooking($remboursementBooking);

        $em->persist($booking);
        $em->flush();

        return new JsonResponse(array(
            'id' => $booking->getId()
        ));
        
    }

    public function notificationAction(Request $request)
    {
        $type_reponse = $request->request->get('type_reponse');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $bookings = $this->getDoctrine()
                    ->getRepository('AppBundle:Booking')
                    ->notifications($agence->getId());

        $checkHebergement = $this->checkHebergement();

        if ($type_reponse == 'html') {
            return $this->render('HebergementBundle:Reservation:notification.html.twig',array(
                'bookings' => $bookings,
                'checkHebergement' => $checkHebergement
            ));
        } else {
            return new JsonResponse($bookings);
        }
    }

    public function exportAction(Request $request)
    {

        $user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        $datas = json_decode(urldecode($request->request->get('datas')));

        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $title = "RÉSERVATION D'HÉBERGEMENT";

        $backgroundTitle = '808080';
        $phpExcelObject->getProperties()->setCreator($title)
            ->setLastModifiedBy($title)
            ->setTitle("Export excel réservation d'hébergement")
            ->setSubject("Export excel réservation d'hébergement")
            ->setDescription("Export excel réservation d'hébergement")
            ->setKeywords("réservation hébergement")
            ->setCategory("export excel");
        $sheet = $phpExcelObject->setActiveSheetIndex(0);

        $this->setExcelHeader($sheet, $agence);

        /*Titre*/
        $sheet->setCellValue('A6', 'N° RÉSERVATION');
        $sheet->setCellValue('B6', 'CHAMBRE');
        $sheet->setCellValue('C6', 'DATE ENTRÉE');
        $sheet->setCellValue('D6', 'DATE SORTIE');
        $sheet->setCellValue('E6', 'NOMBRE DE NUIT');
        $sheet->setCellValue('F6', 'NOMBRE DE PERSONNE');
        $sheet->setCellValue('G6', 'PETIT DÉJEUNER');
        $sheet->setCellValue('H6', 'STATUT');
        $sheet->setCellValue('I6', 'TOTAL');
        

        $sheet->getStyle('A6:I6')
            ->getFill()
            ->setFillType('solid')
            ->getStartColor()->setRGB('f0f0f0');


        foreach(range('A','I') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        $index = 7;

        $totalMontant = 0;

        foreach ($datas as $data) {
           $sheet->setCellValue('A'.$index,$data->num); 
           $sheet->setCellValue('B'.$index,$data->chambre); 
           $sheet->setCellValue('C'.$index,$data->date_entree); 
           $sheet->setCellValue('D'.$index,$data->date_sortie); 
           $sheet->setCellValue('E'.$index,$data->nb_nuit . " nuits"); 
           $sheet->setCellValue('F'.$index,$data->nb_pers . " pers"); 

           if ($data->avec_petit_dejeuner == 1) {
                $sheet->setCellValue('G'.$index,"INCLUS"); 
           } else {
                $sheet->setCellValue('G'.$index,"SUPPLÉMENTAIRE"); 
           }

           switch ($data->statut) {
                case 0:
                    $sheet->setCellValue('H'.$index,"NON CONFIRMÉ"); 
                   break;
                case 1:
                    $sheet->setCellValue('H'.$index,"CONFIRMÉ"); 
                   break;
                case 2:
                    $sheet->setCellValue('H'.$index,"EN COURS"); 
                   break;
                case 3:
                    if ($data->periode == 3) {
                        $sheet->setCellValue('H'.$index,"TERINÉ - À PAYER"); 
                    } else {
                        $sheet->setCellValue('H'.$index,"ENCOURS - À PAYER"); 
                    }
                   break;
                case 4:
                    if ($data->periode == 3) {
                        $sheet->setCellValue('H'.$index,"TERINÉ - PAYÉ"); 
                    } else {
                        $sheet->setCellValue('H'.$index,"ENCOURS - PAYÉ"); 
                    }
                   break;
                case 5:
                    if ($data->montant_remboursement) {
                        if ($data->montant_remboursement != 0) {
                            $sheet->setCellValue('H'.$index,"ANNULÉ AVEC REMBOURSEMENT");
                        } else {
                            $sheet->setCellValue('H'.$index,"ANNULÉ SANS REMBOURSEMENT");
                        }
                    } else {
                        $sheet->setCellValue('H'.$index,"ANNULÉ AUTOMATIQUE");
                    }
                   break;
           }

           $sheet->setCellValue('I'.$index,$data->total); 

           $totalMontant += $data->total;
           $index++;
        }

        $tindex = $index + 1;

        $sheet->setCellValue('A'.$tindex,'Total'); 
        $sheet->setCellValue('I'.$tindex,$totalMontant); 

        // $sheet->getStyle('K'.$tindex)
        //     ->getFill()
        //     ->setFillType('solid')
        //     ->getStartColor()->setRGB('b8e4bb');

        $phpExcelObject->getActiveSheet()->setTitle($title);
        $phpExcelObject->setActiveSheetIndex(0);

        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);

        $ext = '.xls';

        $name = 'reservation-hebergement';


        $name = str_replace('/','-',$name);

        $name .= $ext;

        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $name
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }

    public function setExcelHeader($sheet, $agence)
    {
        $sheet->setCellValue('A1', $agence->getNom());
        $sheet->mergeCells('A1:I1');
        // $sheet->getStyle('A1')
        //     ->getFont()
        //     ->setSize(36);

        $style = array(
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );

        $sheet->getStyle("A1:I1")->applyFromArray($style);

        $sheet->setCellValue('A2', $agence->getAdresse() . " " . $agence->getRegion());
        $sheet->setCellValue('A3', "Tél : " . $agence->getTel());

        $sheet->mergeCells('A2:I2');
        $sheet->getStyle("A2:I2")->applyFromArray($style);
        // $sheet->getStyle('A2')
        //     ->getFont()
        //     ->setSize(20);

        $sheet->mergeCells('A3:I3');
        $sheet->getStyle("A3:I3")->applyFromArray($style);
        // $sheet->getStyle('A3')
        //     ->getFont()
        //     ->setSize(20);

        $sheet->setCellValue('A4', "RÉSERVATION D'HÉBERGEMENT");
        $sheet->mergeCells('A4:I4');
        $sheet->getStyle("A4:I4")->applyFromArray($style);
        // $sheet->getStyle('A4')
        //     ->getFont()
        //     ->setSize(26);


    }

    public function recetteAction()
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

        return $this->render('HebergementBundle:Reservation:recette.html.twig', array(
            'agences' => $agences,
            'chambres' => $chambres,
            'categories' => $categories,
            'userAgence' => $userAgence,
        ));
    }

    public function cancelNightEditorAction($id)
    {
        $booking = $this->getDoctrine()->getRepository('AppBundle:Booking')
            ->find($id);

        $now = new \DateTime('now 00:00');
        $start = $booking->getDateEntree();
        $end = $booking->getDateSortie();


        if ($now >= $start) {
            $nb_nuit = $end->diff( $now )->days;
            $date_calcul = $now;
        } else {
            $nb_nuit = $end->diff( $start )->days;
            $date_calcul = $start;
        }

        return $this->render('@Hebergement/Reservation/cancel-night.html.twig',[
            'booking' => $booking,
            'nb_nuit' => $nb_nuit,
            'date_calcul' => $date_calcul,
        ]);
    }

    public function cancelNightSaveAction(Request $request)
    {
        $booking = $request->request->get('booking');
        $nb_nuit = $request->request->get('nb_nuit');
        $pourcentage = $request->request->get('pourcentage');
        $montant = $request->request->get('montant');
        $ancien_date_sortie = $request->request->get('ancien_date_sortie');
        $nouveau_date_sortie = $request->request->get('nouveau_date_sortie');

        $booking = $this->getDoctrine()->getRepository('AppBundle:Booking')
            ->find($booking);

        $date_annulation = new \DateTime('now');
        $ancien_nb_nuit = $booking->getNbNuit();
        $ancien_date_sortie = $booking->getDateSortie();
        $nouveau_date_sortie = \DateTime::createFromFormat('j/m/Y', $nouveau_date_sortie);

        $annulation = new AnnulationNuit();

        $annulation->setDate($date_annulation);
        $annulation->setNbNuit($nb_nuit);
        $annulation->setAncienNbNuit($ancien_nb_nuit);
        $annulation->setPourcentage($pourcentage);
        $annulation->setMontant($montant);
        $annulation->setAncienDateSortie($ancien_date_sortie);
        $annulation->setNouveauDateSortie($nouveau_date_sortie);
        $annulation->setBooking($booking);

        $em = $this->getDoctrine()->getManager();
        $em->persist($annulation);
        $em->flush();

        $remboursementBooking = new RemboursementBooking();

        $remboursementBooking->setPourcentage($pourcentage);
        $remboursementBooking->setMontant($montant);

        $em->persist($remboursementBooking);
        $em->flush();

        $booking->setNbNuit( $ancien_nb_nuit - $nb_nuit );
        $booking->setDateSortie($nouveau_date_sortie);
        $booking->setRemboursementBooking($remboursementBooking);

        $em->persist($booking);
        $em->flush();

        return new JsonResponse(array(
            'id' => $booking->getId()
        ));

    }

    public function ticketAction(Request $request)
    {
        $id = $request->request->get('id');
        $printer_name = $request->request->get('printer_name');

        $booking = $this->getDoctrine()
                ->getRepository('AppBundle:Booking')
                ->find($id);

        $data = $this->makeTicketData($booking);

        return new JsonResponse(array(
            'data' => $data
        ));

    }

    public function makeTicketData($booking)
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $data = array(
            'agence' => $agence->getNom(),
            'adresse' => $agence->getAdresse() ? $agence->getAdresse() : $agence->getRegion(),
            'tel' => $agence->getTel(),
            'recu' => "Réservation N° " . $booking->getNum(),
            'qrcode' => $booking->getNum(),
            'date' => "Le ". $booking->getDate()->format('d/m/Y'),
            'thead' => ["Détails de la réservation"],
            'tbody' => [],
            'tfoot' => ["Total","",$booking->getTotal()],
            'caissier' => [ "Caissier :",$user->getUsername() ],
        );

        $data['tbody'] = $this->addRow( $data['tbody'], array(
            'designation' => 'Chambre',
            'valeur' => $booking->getChambre()->getNom(),
        ) );

        $data['tbody'] = $this->addRow( $data['tbody'], array(
            'designation' => 'Nb pers',
            'valeur' => $booking->getNbPers(),
        ) );

        $data['tbody'] = $this->addRow( $data['tbody'], array(
            'designation' => 'Petit dejeuner',
            'valeur' => ($booking->getAvecPetitDejeuner() == 1) ? "Inclus" : $booking->getMontantPetitDejeuner(),
        ) );

        $data['tbody'] = $this->addRow( $data['tbody'], array(
            'designation' => 'Nb nuits',
            'valeur' => $booking->getNbNuit(),
        ) );

        return $data;
    }

    public function addRow($tbody, $item)
    {
        $designation = str_split($item['designation'], 16);
        
        if (count($designation) > 1) {
            for ($i=0; $i < count($designation); $i++) { 
                $value = $designation[$i];
                if ($i == count($designation) - 1) {
                    $tr = [ $designation[$i] , " " . $item['valeur'] ];
                    array_push($tbody, $tr);
                } else {
                    array_push($tbody, [$value . " "]);
                }
            }
        } else {
            $tr = [ $designation[0] , " " . $item['valeur'] ];
            array_push($tbody, $tr);
        }

        return $tbody;

    }

    public function deleteAction($id)
    {
        $booking  = $this->getDoctrine()
                        ->getRepository('AppBundle:Booking')
                        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($booking);
        $em->flush();

        return new JsonResponse(200);
        
    }
}

