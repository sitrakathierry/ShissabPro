<?php

namespace FactureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Facture;
use AppBundle\Entity\FactureHebergement;
use AppBundle\Entity\FactureHebergementDetails;
use AppBundle\Entity\FactureRestaurant;
use AppBundle\Entity\FactureRestaurantDetails;

class FactureHebergementController extends Controller
{
	public function saveAction(Request $request)
    {
        $f_type = $request->request->get('f_type');
        $f_model = $request->request->get('f_model');
        $f_client = $request->request->get('f_client');
        $f_date = $request->request->get('f_date');
        $f_lieu = $request->request->get('f_lieu');
        $descr = $request->request->get('descr');

        $montant = $request->request->get('total_heb_resto');
        $f_remise = $request->request->get('f_hebergement_remise');
        $remise = $request->request->get('hebergement_remise');
        $f_remise_type = $request->request->get('f_hebergement_remise_type');
        $total = $request->request->get('hebergement_total');
        $somme = $request->request->get('somme_hebergement');
        $f_is_credit = $request->request->get('f_is_credit');
        $booking = $request->request->get('booking');
        $f_heb_devise = $request->request->get('f_heb_devise');
        $f_heb_montant_converti = $request->request->get('f_heb_montant_converti');

        $f_id = $request->request->get('f_id');
        $list_id = $request->request->get('list_id');

        $client = $this->getDoctrine()
            ->getRepository('AppBundle:Client')
            ->find($f_client);

        $agence = $client->getAgence();

        if ($f_id) {
            $facture = $this->getDoctrine()
                ->getRepository('AppBundle:Facture')
                ->find($f_id);
             $newNum = str_pad($facture->getNum(), 3, '0', STR_PAD_LEFT);

             $factureHebergement = $this->getDoctrine()
                ->getRepository('AppBundle:FactureHebergement')
                ->findOneBy(array(
                    'facture' => $facture
                ));

            $factureRestaurant = $this->getDoctrine()
                ->getRepository('AppBundle:FactureRestaurant')
                ->findOneBy(array(
                    'facture' => $facture
                ));
        } else{
            $facture = new Facture();
            $newNum = $this->prepareNewNumFacture($agence->getId());
            $facture->setNum(intval($newNum));

            $factureHebergement = new FactureHebergement();

            $factureRestaurant = new FactureRestaurant();

        }

        $facture->setType($f_type);
        $facture->setModele($f_model);
        $facture->setMontant($montant);
        $facture->setRemiseType($f_remise_type);
        $facture->setRemisePourcentage($f_remise);
        $facture->setRemiseValeur($remise);
        $facture->setTotal($total);
        $facture->setSomme($somme);
        $facture->setDescr($descr);
        $facture->setClient($client);
        $facture->setIsCredit($f_is_credit);

        if ($f_heb_devise) {
            $devise = $this->getDoctrine()
                ->getRepository('AppBundle:Devise')
                ->find($f_heb_devise);
            
            $facture->setDevise($devise);
            $facture->setMontantConverti($f_heb_montant_converti);
        }


        $dateCreation = new \DateTime('now');
        $facture->setDateCreation($dateCreation);
        
        $date = \DateTime::createFromFormat('j/m/Y', $f_date);

        $facture->setDate($date);
        $facture->setLieu($f_lieu);

        $facture->setAgence($agence);

        $em = $this->getDoctrine()->getManager();
        $em->persist($facture);
        $em->flush();

        $hebergement_montant = $request->request->get('hebergement_montant');

        $factureHebergement->setTotal($hebergement_montant);
        $factureHebergement->setFacture($facture);

        if ($booking) {
            $booking = $this->getDoctrine()
                ->getRepository('AppBundle:Booking')
                ->find($booking);

            $factureHebergement->setBooking($booking);
        }


        $em->persist($factureHebergement);
        $em->flush();

        $details_list = explode(",", $list_id);

        // Suppression de tous les details
        foreach ($details_list as $old_id) {

            if ($old_id != "") {
                $old_detail = $this->getDoctrine()
                                    ->getRepository('AppBundle:FactureHebergementDetails')
                                    ->find($old_id);

                $em->remove($old_detail);
                $em->flush();
            }

        }

        $f_hebergement_nb_pers = $request->request->get('f_hebergement_nb_pers');
        $f_hebergement_date_entree = $request->request->get('f_hebergement_date_entree');
        $f_hebergement_date_sortie = $request->request->get('f_hebergement_date_sortie');
        $f_hebergement_nb_nuit = $request->request->get('f_hebergement_nb_nuit');
        $f_hebergement_chambre = $request->request->get('f_hebergement_chambre');
        $f_hebergement_avec_petit_dejeuner = $request->request->get('f_hebergement_avec_petit_dejeuner');
        $f_hebergement_montant = $request->request->get('f_hebergement_montant');


        if (!empty($f_hebergement_nb_pers)) {
            foreach ($f_hebergement_nb_pers as $key => $value) {
                $detail = new FactureHebergementDetails();

                $nb_pers = $f_hebergement_nb_pers[$key];
                $date_entree = $f_hebergement_date_entree[$key];
                $date_sortie = $f_hebergement_date_sortie[$key];
                $nb_nuit = $f_hebergement_nb_nuit[$key];
                $chambre = $f_hebergement_chambre[$key];
                $avec_petit_dejeuner = $f_hebergement_avec_petit_dejeuner[$key];
                $montant = $f_hebergement_montant[$key];
               
                $chambre = $this->getDoctrine()
                        ->getRepository('AppBundle:Chambre')
                        ->find( $chambre );

                $detail->setNbPers($nb_pers);
                $detail->setDateEntree(\DateTime::createFromFormat('j/m/Y', $date_entree));
                $detail->setDateSortie(\DateTime::createFromFormat('j/m/Y', $date_sortie));
                $detail->setNbNuit($nb_nuit);
                $detail->setChambre($chambre);
                $detail->setAvecPetitDejeuner($avec_petit_dejeuner);
                $detail->setMontant($montant);
                $detail->setFactureHebergement($factureHebergement);

                $em->persist($detail);
                $em->flush();

            }
        }

        // facture restaurant
        $plats = $request->request->get('plat');
        $qte = $request->request->get('qte');
        $prix = $request->request->get('prix');
        $total = $request->request->get('total');
        $statut_details = $request->request->get('statut_detail');
        $accompagnement_details = $request->request->get('accompagnement_details');

        if (!empty($plats)) {

            if ($plats[0] != '') {
                $montant_total = $request->request->get('montant_total');

                $factureRestaurant->setTotal($montant_total);
                $factureRestaurant->setFacture($facture);

                $em->persist($factureRestaurant);
                $em->flush();

                foreach ($plats as $key => $value) {
                    $detail = new FactureRestaurantDetails();

                    $plat_detail = $plats[$key];
                    $qte_detail = $qte[$key];
                    $prix_detail = $prix[$key];
                    $total_detail = $total[$key];
                    $statut_detail = $statut_details[$key];
                    $accompagnements = json_decode($accompagnement_details)[$key];

                    $plat = $this->getDoctrine()
                            ->getRepository('AppBundle:Plat')
                            ->find( $plat_detail );

                    $detail->setPlat($plat);
                    $detail->setQte($qte_detail);
                    $detail->setPrix($prix_detail);
                    $detail->setTotal($total_detail);
                    $detail->setStatut($statut_detail);
                    $detail->setFactureRestaurant($factureRestaurant);
                    $detail->setAccompagnements( json_encode( $accompagnements ) );

                    $em->persist($detail);
                    $em->flush();

                }
            }

        }

        if ($f_is_credit == 1 && !$facture->getCredit()) {
            $this->saveCredit($facture);
        }

        return $this->redirectToRoute('facture_hebergement_show',array('id' => $facture->getId()));

    }

    public function prepareNewNumFacture($id_agence)
    {
        $em = $this->getDoctrine()
                ->getManager();
        $newNum = $em
            ->getRepository("AppBundle:Facture")
            ->newNum($id_agence);

        return $newNum;

    }

    public function showAction($id)
    {
        $facture  = $this->getDoctrine()
                        ->getRepository('AppBundle:Facture')
                        ->find($id);

        $factureHebergement  = $this->getDoctrine()
                        ->getRepository('AppBundle:FactureHebergement')
                        ->findOneBy(array(
                        	'facture' => $facture
                        ));

        $definitif = $this->getDoctrine()
                        ->getRepository('AppBundle:Facture')
                        ->findOneBy(array(
                            'proforma' => $facture
                        ));

        $details = $this->getDoctrine()
                    ->getRepository('AppBundle:FactureHebergementDetails')
                    ->findBy(array(
                        'factureHebergement' => $factureHebergement
                    ));

        $factureRestaurant  = $this->getDoctrine()
                        ->getRepository('AppBundle:FactureRestaurant')
                        ->findOneBy(array(
                            'facture' => $facture
                        ));

        $restaurantDetails = $this->getDoctrine()
                ->getRepository('AppBundle:FactureRestaurantDetails')
                ->findBy(array(
                    'factureRestaurant' => $factureRestaurant,
                ));


        foreach ($restaurantDetails as $detail) {
            $accs = $this->getDoctrine()
                ->getRepository('AppBundle:FactureRestaurantDetails')
                ->accompagnementsDetails($detail->getAccompagnements());

            $detail->setAccompagnementsList($accs['accompagnements']);
            $detail->setTotalAccompagnement($accs['total_accompagnement']);
        }

        $permission_user = $this->get('app.permission_user');
        $user = $this->getUser();
        $permissions = $permission_user->getPermissions($user);

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $clients = $this->getDoctrine()
            ->getRepository('AppBundle:Client')
            ->findBy(array(
                'agence' => $agence
            ));

        $chambres = $this->getDoctrine()
            ->getRepository('AppBundle:Chambre')
            ->findBy(array(
                'agence' => $agence
            ));

        $print = false;

        $pdfs = $this->getDoctrine()
                    ->getRepository('AppBundle:PdfAgence')
                    ->findBy(array(
                        'agence' => $agence,
                        'objet' => 1,
                    ));
        if (!empty($pdfs)) {
            $print = true;
        }

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

        $devises = $this->getDoctrine()
                    ->getRepository('AppBundle:Devise')
                    ->findBy(array(
                        'agence' => $agence
                    ));

        $deviseEntrepot = $this->getDevise();

        return $this->render('FactureBundle:FactureHebergement:show.html.twig', array(
            'agence' => $agence,
            'deviseEntrepot' => $deviseEntrepot,
            'devises' => $devises,
            'facture' => $facture,
            'factureHebergement' => $factureHebergement,
            'details' => $details,
            'chambres' => $chambres,
            'clients' => $clients,
            'permissions' => $permissions,
            'print' => $print,
            'definitif' => $definitif,
            'plats' => $plats,
            'accompagnements' => $accompagnements,
            'factureRestaurant' => $factureRestaurant,
            'restaurantDetails' => $restaurantDetails,
        ));

    }

    public function pdfAction($id)
    {
        $facture  = $this->getDoctrine()
                        ->getRepository('AppBundle:Facture')
                        ->find($id);

        $factureHebergement  = $this->getDoctrine()
                        ->getRepository('AppBundle:FactureHebergement')
                        ->findOneBy(array(
                            'facture' => $facture
                        ));

        $details = $this->getDoctrine()
                    ->getRepository('AppBundle:FactureHebergementDetails')
                    ->findBy(array(
                        'factureHebergement' => $factureHebergement
                    ));

        $factureRestaurant  = $this->getDoctrine()
                        ->getRepository('AppBundle:FactureRestaurant')
                        ->findOneBy(array(
                            'facture' => $facture
                        ));

        $restaurantDetails = $this->getDoctrine()
                ->getRepository('AppBundle:FactureRestaurantDetails')
                ->findBy(array(
                    'factureRestaurant' => $factureRestaurant,
                ));

        foreach ($restaurantDetails as $detail) {
            $accs = $this->getDoctrine()
                ->getRepository('AppBundle:FactureRestaurantDetails')
                ->accompagnementsDetails($detail->getAccompagnements());

            $detail->setAccompagnementsList($accs['accompagnements']);
            $detail->setTotalAccompagnement($accs['total_accompagnement']);
        }


        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $chambres = $this->getDoctrine()
            ->getRepository('AppBundle:Chambre')
            ->findBy(array(
                'agence' => $agence
            ));

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
            
        $pdfAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:PdfAgence')
                    ->findOneBy(array(
                        'agence' => $agence
                    ));       

        $modelePdf = $facture->getModelePdf(); 

        $deviseEntrepot = $this->getDevise();

        $template = $this->renderView('FactureBundle:FactureHebergement:pdf.html.twig', array(
            'agence' => $agence,
            'deviseEntrepot' => $deviseEntrepot,
            'facture' => $facture,
            'factureHebergement' => $factureHebergement,
            'details' => $details,
            'chambres' => $chambres,
            'modelePdf' => $modelePdf,
            'factureRestaurant' => $factureRestaurant,
            'restaurantDetails' => $restaurantDetails,
            'plats' => $plats,
            'accompagnements' => $accompagnements,
        ));

        $html2pdf = $this->get('app.html2pdf');

        $html2pdf->create();

        return $html2pdf->generatePdf($template, "facture" . $facture->getId());

    }

    public function bookingAction($booking)
    {
        $booking = $this->getDoctrine()
                ->getRepository('AppBundle:Booking')
                ->find($booking);

        $tpl = $this->renderView('FactureBundle:FactureHebergement:booking.html.twig',array(
            'booking' => $booking,
        ));

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

        $total_resto = $this->totalCommandes($commandes);

        $tplRestaurant = $this->renderView('FactureBundle:FactureRestaurant:commandes.html.twig',array(
            'commandes' => $commandes,
            'plats' => $plats,
            'accompagnements' => $accompagnements,
        ));

        $remise = $booking->getRemise();
        $type_remise = $booking->getTypeRemise();
        $total = $booking->getTotal();

        if ($type_remise == 0) {
            $hebergement_a_payer = $total - $remise;
            $remise_montant = $remise;
        } else {
            $remise_montant = ($total * $remise) / 100;
            $hebergement_a_payer = $total - ($remise_montant);
        }


        return new JsonResponse(array(
            'client_id' => $booking->getClient() ? $booking->getClient()->getNumPolice() : '',
            'remise_montant' => $remise_montant,
            'remise' => $remise,
            'type_remise' => $type_remise,
            'hebergement_a_payer' => $hebergement_a_payer,
            'total_resto' => $total_resto,
            'tpl' => $tpl,
            'tplRestaurant' => $tplRestaurant,
        ));
    }

    public function totalCommandes($commandes)
    {
        $total = 0;

        foreach ($commandes as $commande) {
            $total += $commande['total'];
        }

        return $total;
    }

    public function creerDefinitifAction($id)
    {
        $facture  = $this->getDoctrine()
                        ->getRepository('AppBundle:Facture')
                        ->find($id);

        $this->duplicateToDefinitif($facture);

        return new JsonResponse(array(
            'id' => $facture->getNum()
        ));

        
    }

    public function duplicateToDefinitif($facture)
    {

        $factureHebergement  = $this->getDoctrine()
                        ->getRepository('AppBundle:FactureHebergement')
                        ->findOneBy(array(
                            'facture' => $facture
                        ));

        $details = $this->getDoctrine()
                    ->getRepository('AppBundle:FactureHebergementDetails')
                    ->findBy(array(
                        'factureHebergement' => $factureHebergement
                    ));

        $factureDefinitif = new Facture();
        $factureHebergementDefinitif = new FactureHebergement();
        $num = $facture->getNum();
        $f_type = 2;
        $f_model = $facture->getModele();
        $montant = $facture->getMontant();
        $f_remise_type = $facture->getRemiseType();
        $f_remise = $facture->getRemisePourcentage();
        $remise = $facture->getRemiseValeur();
        $total = $facture->getTotal();
        $somme = $facture->getSomme();
        $descr = $facture->getDescr();
        $client = $facture->getClient();
        $date = $facture->getDate();
        $agence = $facture->getAgence();
        $f_is_credit = $facture->getIsCredit();
        $dateCreation = new \DateTime('now');

        $factureDefinitif->setNum($num);
        $factureDefinitif->setType($f_type);
        $factureDefinitif->setModele($f_model);
        $factureDefinitif->setMontant($montant);
        $factureDefinitif->setRemiseType($f_remise_type);
        $factureDefinitif->setRemisePourcentage($f_remise);
        $factureDefinitif->setRemiseValeur($remise);
        $factureDefinitif->setTotal($total);
        $factureDefinitif->setSomme($somme);
        $factureDefinitif->setDescr($descr);
        $factureDefinitif->setClient($client);
        $factureDefinitif->setIsCredit($f_is_credit);
        $factureDefinitif->setDateCreation($dateCreation);
        $factureDefinitif->setDate($date);
        $factureDefinitif->setAgence($agence);
        $factureDefinitif->setProforma($facture);

        $em = $this->getDoctrine()->getManager();
        $em->persist($factureDefinitif);
        $em->flush();

        $factureHebergementDefinitif->setFacture($factureDefinitif);

        $em->persist($factureHebergementDefinitif);
        $em->flush();

        foreach ($details as $detail) {
            $detailDefinitif = new FactureHebergementDetails();

            $nb_pers = $detail->getNbPers();
            $date_entree = $detail->getDateEntree();
            $date_sortie = $detail->getDateSortie();
            $nb_nuit = $detail->getNbNuit();
            $chambre = $detail->getChambre();
            $avec_petit_dejeuner = $detail->getAvecPetitDejeuner();
            $montant = $detail->getMontant();

            $detailDefinitif->setNbPers($nb_pers);
            $detailDefinitif->setDateEntree($date_entree);
            $detailDefinitif->setDateSortie($date_sortie);
            $detailDefinitif->setNbNuit($nb_nuit);
            $detailDefinitif->setChambre($chambre);
            $detailDefinitif->setAvecPetitDejeuner($avec_petit_dejeuner);
            $detailDefinitif->setMontant($montant);
            $detailDefinitif->setFactureHebergement($factureHebergementDefinitif);

            $em->persist($detailDefinitif);
            $em->flush();
            
        }

        $factureRestaurant  = $this->getDoctrine()
                        ->getRepository('AppBundle:FactureRestaurant')
                        ->findOneBy(array(
                            'facture' => $facture
                        ));

        if ($factureRestaurant) {
            $this->duplicateRestaurant($factureDefinitif, $factureRestaurant);
        }

        return $factureDefinitif->getId();

    }

    public function duplicateRestaurant($factureDefinitif, $factureRestaurant)
    {
        $details = $this->getDoctrine()
            ->getRepository('AppBundle:FactureRestaurantDetails')
            ->findBy(array(
                'factureRestaurant' => $factureRestaurant
            ));

        $factureRestaurantDefinitif = new FactureRestaurant();

        $montant_total = $factureRestaurant->getTotal();

        $factureRestaurantDefinitif->setTotal($montant_total);
        $factureRestaurantDefinitif->setFacture($factureDefinitif);

        $em = $this->getDoctrine()->getManager();
        $em->persist($factureRestaurantDefinitif);
        $em->flush();

        foreach ($details as $detail) {
            $detailDefinitif = new FactureRestaurantDetails();

            $plat_detail = $detail->getPlat();
            $qte_detail = $detail->getQte();
            $prix_detail = $detail->getPrix();
            $total_detail = $detail->getTotal();
            $statut_detail = $detail->getStatut();
            $accompagnements = $detail->getAccompagnements();
            
            
            $detailDefinitif->setPlat($plat_detail);
            $detailDefinitif->setQte($qte_detail);
            $detailDefinitif->setPrix($prix_detail);
            $detailDefinitif->setTotal($total_detail);
            $detailDefinitif->setStatut($statut_detail);
            $detailDefinitif->setFactureRestaurant($factureRestaurantDefinitif);
            $detailDefinitif->setAccompagnements( $accompagnements );

            $em->persist($detailDefinitif);
            $em->flush();
            
        }
    }

    public function getDevise()
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $agence = $userAgence->getAgence();

        $userEntrepot = $this->getDoctrine()
                    ->getRepository('AppBundle:UserEntrepot')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $devise = array(
            'symbole' => $agence->getDeviseSymbole(),
            'lettre' => $agence->getDeviseLettre(),
        );

        if ($userEntrepot) {

            $entrepot = $userEntrepot->getEntrepot();


            if ($entrepot) {
                if ($entrepot->getDeviseSymbole()) {
                    $devise = array(
                        'symbole' => $entrepot->getDeviseSymbole(),
                        'lettre' => $entrepot->getDeviseLettre(),
                    );
                }
            }
        }


        return $devise;
        

    }
}
