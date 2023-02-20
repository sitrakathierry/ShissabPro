<?php

namespace BonLivraisonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\BonLivraison;
use AppBundle\Entity\BonLivraisonDetails;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BonLivraisonBundle:Default:index.html.twig');
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

        $clients = $this->getDoctrine()
            ->getRepository('AppBundle:Client')
            ->findBy(array(
                'agence' => $agence
            ));

        $bonCommandes = $this->getDoctrine()
            ->getRepository('AppBundle:BonCommande')
            ->findBy(array(
                'agence' => $agence,
            ));

        $factures = $this->getDoctrine()
            ->getRepository('AppBundle:Facture')
            ->findBy(array(
                'agence' => $agence,
                'modele' => [1,2,3],
                'type'   => 2
            ));

        return $this->render('BonLivraisonBundle:Default:add.html.twig', array(
            'clients' => $clients,
            'agence' => $agence,
            'bonCommandes' => $bonCommandes,
            'userAgence' => $userAgence,
            'factures'   => $factures
        ));
    }

    public function saveAction(Request $request)
    {

        $id = $request->request->get('id');
        $date = $request->request->get('date');
        $bon_commande = $request->request->get('bon_commande');
        $client = $request->request->get('client');
        $statut = $request->request->get('statut');
        $date = \DateTime::createFromFormat('j/m/Y', $date);
        $lieu = $request->request->get('lieu');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        if ($id) {
            $bonLivraison = $this->getDoctrine()
                ->getRepository('AppBundle:BonLivraison')
                ->find($id);
        } else {
            $bonLivraison = new BonLivraison();
        }

        if ($client) {
            $client = $this->getDoctrine()
                    ->getRepository('AppBundle:Client')
                    ->find($client);
            $bonLivraison->setClient($client);
        }

        $bonLivraison->setDate($date);
        $bonLivraison->setLieu($lieu);
        $bonLivraison->setAgence($agence);
        $bonLivraison->setStatut($statut ? $statut : 1); // statut 1 : en cours

        if ($bon_commande) {
            $bonCommande = $this->getDoctrine()
                    ->getRepository('AppBundle:BonCommande')
                    ->find($bon_commande);

            $bonLivraison->setBonCommande($bonCommande);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($bonLivraison);
        $em->flush();

        $type_designation = $request->request->get('type_designation');
        $designations = $request->request->get('designation');
        $f_ps_qte = $request->request->get('f_ps_qte');
        $f_ps_periode = $request->request->get('f_ps_periode');
        $f_ps_duree = $request->request->get('f_ps_duree');
        $description_detail = $request->request->get('description_detail');

        $details = $this->getDoctrine()
                    ->getRepository('AppBundle:BonLivraisonDetails')
                    ->findBy(array(
                        'bonLivraison' => $bonLivraison
                    ));

        foreach ($details as $detail) {
            $em->remove($detail);
            $em->flush();
        }

        if (!empty($type_designation)) {
            foreach ($type_designation as $key => $value) {

                $detail = new BonLivraisonDetails();

                $type = $type_designation[$key];
                $designation = $designations[$key];
                $qte = $f_ps_qte[$key];
                $periode = $f_ps_periode[$key];
                $duree = $f_ps_duree[$key];
                $description = $description_detail[$key];

                $detail->setType($type);

                if ($type == 1) {
                    $variation = $this->getDoctrine()
                                        ->getRepository('AppBundle:VariationProduit')
                                        ->find( $designation );
                    $detail->setVariationProduit($variation);
                    $detail->setQte($qte);
                }

                if ($type == 2) {
                    $service = $this->getDoctrine()
                                        ->getRepository('AppBundle:Service')
                                        ->find( $designation );
                    $detail->setService($service);
                    $detail->setDuree($duree);
                    $detail->setPeriode($periode);

                }

                if ($type == 3) {
                    $detail->setDesignationAutre($designation_autre);
                    $detail->setQte($qte);
                }
                
                $detail->setDescription($description);
                $detail->setBonLivraison($bonLivraison);

                $em->persist($detail);
                $em->flush();
            
            }
        }

        return new JsonResponse(array(
            'id' => $bonLivraison->getId()
        ));
    
    }

    public function consultationAction()
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        return $this->render('BonLivraisonBundle:Default:consultation.html.twig',array(
            'userAgence' => $userAgence
        ));
    }

    public function consultationCorbeilleAction()
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        return $this->render('BonLivraisonBundle:Default:consultation_corbeille.html.twig',array(
            'userAgence' => $userAgence
        ));
    }

    public function listAction(Request $request)
    {

        $agence = $request->request->get('agence');
        $type_date = $request->request->get('type_date');
        $mois = $request->request->get('mois');
        $annee = $request->request->get('annee');
        $date_specifique = $request->request->get('date_specifique');
        $debut_date = $request->request->get('debut_date');
        $fin_date = $request->request->get('fin_date');

        $livraisons = $this->getDoctrine()
                        ->getRepository('AppBundle:BonLivraison')
                        ->consultation(
                            $agence,
                            0,
                            $type_date,
                            $mois,
                            $annee,
                            $date_specifique,
                            $debut_date,
                            $fin_date
                        );


        $data = array();

        foreach ($livraisons as $livraison) {
            $details = $this->getDoctrine()
                    ->getRepository('AppBundle:BonLivraisonDetails')
                    ->consultation($livraison['id']);


            $livraison['details'] = null;

            if (!empty($details)) {
                $livraison['details'] = $details;
            }

            array_push($data, $livraison);
        }

        return $this->render('BonLivraisonBundle:Default:list.html.twig',array(
            'livraisons' => $data
        ));
        
    }

    public function listCorbeilleAction(Request $request)
    {

        $agence = $request->request->get('agence');
        $type_date = $request->request->get('type_date');
        $mois = $request->request->get('mois');
        $annee = $request->request->get('annee');
        $date_specifique = $request->request->get('date_specifique');
        $debut_date = $request->request->get('debut_date');
        $fin_date = $request->request->get('fin_date');

        $livraisons = $this->getDoctrine()
                        ->getRepository('AppBundle:BonLivraison')
                        ->consultationCorbeille(
                            $agence,
                            0,
                            $type_date,
                            $mois,
                            $annee,
                            $date_specifique,
                            $debut_date,
                            $fin_date
                        );


        $data = array();

        foreach ($livraisons as $livraison) {
            $details = $this->getDoctrine()
                    ->getRepository('AppBundle:BonLivraisonDetails')
                    ->consultation($livraison['id']);


            $livraison['details'] = null;

            if (!empty($details)) {
                $livraison['details'] = $details;
            }

            array_push($data, $livraison);
        }

        return $this->render('BonLivraisonBundle:Default:list_corbeille.html.twig',array(
            'livraisons' => $data
        ));
        
    }

    public function showAction($id)
    {
        $livraison = $this->getDoctrine()
                        ->getRepository('AppBundle:BonLivraison')
                        ->find($id);

        $details = $this->getDoctrine()
                    ->getRepository('AppBundle:BonLivraisonDetails')
                    ->findBy(array(
                        'bonLivraison' => $livraison
                    ));

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence()->getId();

        $variations = $this->getDoctrine()
                ->getRepository('AppBundle:VariationProduit')
                ->list($agence);

        $services = $this->getDoctrine()
                ->getRepository('AppBundle:Service')
                ->getList($agence);

        $clients = $this->getDoctrine()
            ->getRepository('AppBundle:Client')
            ->findBy(array(
                'agence' => $agence
            ));

        $bonCommande = $livraison->getBonCommande();

        return $this->render('BonLivraisonBundle:Default:show.html.twig',array(
            'livraison' => $livraison,
            'bonCommande' => $bonCommande,
            'details' => $details,
            'clients' => $clients,
            'variations' => $variations,
            'services' => $services,
            'userAgence' => $userAgence,
        ));
    }

    public function pdfAction($id)
    {
        $bonLivraison  = $this->getDoctrine()
                        ->getRepository('AppBundle:BonLivraison')
                        ->find($id);

        $details = $this->getDoctrine()
                    ->getRepository('AppBundle:BonLivraisonDetails')
                    ->findBy(array(
                        'bonLivraison' => $bonLivraison
                    ));


        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

            
        $pdfAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:PdfAgence')
                    ->findOneBy(array(
                        'agence' => $agence
                    ));       

        $modelePdf = $bonLivraison->getModelePdf();      


        $template = $this->renderView('BonLivraisonBundle:Default:pdf.html.twig', array(
            'bonLivraison' => $bonLivraison,
            'details' => $details,
            'modelePdf' => $modelePdf,
        ));

        $html2pdf = $this->get('app.html2pdf');

        $html2pdf->create();

        return $html2pdf->generatePdf($template, "bonLivraison" . $bonLivraison->getId());

    }

    public function validationAction($id)
    {
        $bonLivraison = $this->getDoctrine()
                        ->getRepository('AppBundle:BonLivraison')
                        ->find($id);

        $bonLivraison->setStatut(2); // statut 2 : validé

        $em = $this->getDoctrine()->getManager();
        $em->persist($bonLivraison);
        $em->flush();

        return new JsonResponse(array(
            'id' => $bonLivraison->getId()
        ));
    }

    public function deleteAction($id)
    {
        $bonLivraison  = $this->getDoctrine()
                        ->getRepository('AppBundle:BonLivraison')
                        ->find($id);

        $bon_livraison_details = ($bonLivraison) ? $this->getDoctrine()
                                ->getRepository('AppBundle:BonLivraisonDetails')
                                ->findBy(array(
                                    'bonLivraison' => $bonLivraison
                                )) : null ;

        $em = $this->getDoctrine()->getManager();

        for ($i=0; $i < count($bon_livraison_details) ; $i++) {
                $em->remove($bon_livraison_details[$i]);
                $em->flush();
        }

        $em->remove($bonLivraison);
        $em->flush();
        

        return new JsonResponse(200);
    }

    public function bonCommandeAction($bonCommande)
    {
        $bonCommande  = $this->getDoctrine()
                        ->getRepository('AppBundle:BonCommande')
                        ->find($bonCommande);

        $panniers = $this->getDoctrine()
                    ->getRepository('AppBundle:PannierBon')
                    ->findBy(array(
                        'bonCommande' => $bonCommande
                    ));

        $tpl = $this->renderView('BonLivraisonBundle:BonCommande:tpl.html.twig',array(
            'panniers' => $panniers,
        ));

        return new JsonResponse(array(
            'tpl' => $tpl,
            'client_id' => $bonCommande->getClient()->getNumPolice(),
        ));
    }

    public function factureAction($facture)
    {
        $facture  = $this->getDoctrine()
                        ->getRepository('AppBundle:Facture')
                        ->find($facture);

        $modele = $facture->getModele();

        if ($modele == 1) {
            $factureProduit  = $this->getDoctrine()
                        ->getRepository('AppBundle:FactureProduit')
                        ->findOneBy(array(
                            'facture' => $facture
                        ));

            $details = $this->getDoctrine()
                    ->getRepository('AppBundle:FactureProduitDetails')
                    ->findBy(array(
                        'factureProduit' => $factureProduit
                    ));

            $tpl = $this->renderView('BonLivraisonBundle:FactureProduit:tpl.html.twig',array(
                'details' => $details,
            ));
        }

        if ($modele == 2) {
            $factureService  = $this->getDoctrine()
                        ->getRepository('AppBundle:FactureService')
                        ->findOneBy(array(
                            'facture' => $facture
                        ));

            $details = $this->getDoctrine()
                    ->getRepository('AppBundle:FactureServiceDetails')
                    ->findBy(array(
                        'factureService' => $factureService
                    ));

            $tpl = $this->renderView('BonLivraisonBundle:FactureService:tpl.html.twig',array(
                'details' => $details,
            ));
        }

        if ($modele == 3) {
            $factureProduitService  = $this->getDoctrine()
                        ->getRepository('AppBundle:FactureProduitService')
                        ->findOneBy(array(
                            'facture' => $facture
                        ));

            $details = $this->getDoctrine()
                    ->getRepository('AppBundle:FactureProduitServiceDetails')
                    ->findBy(array(
                        'factureProduitService' => $factureProduitService
                    ));

            $tpl = $this->renderView('BonLivraisonBundle:FactureProduitService:tpl.html.twig',array(
                'details' => $details,
            ));
        }

        return new JsonResponse(array(
            'tpl' => $tpl,
            'client_id' => $facture->getClient()->getNumPolice(),
        ));

    }

}
