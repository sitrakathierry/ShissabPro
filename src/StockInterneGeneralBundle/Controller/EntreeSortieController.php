<?php

namespace StockInterneGeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\StockInterneGeneral;
use AppBundle\Entity\EntreeSortieStockInterneGeneral;
use AppBundle\Entity\EntreeSortieStockInterneGeneralDetails;

class EntreeSortieController extends Controller
{
	public function indexAction()
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $stockInternes = $this->getDoctrine()
                    ->getRepository('AppBundle:StockInterneGeneral')
                    ->findBy(array(
                        'agence' => $agence
                    ));

        return $this->render('StockInterneGeneralBundle:EntreeSortie:index.html.twig',array(
            'stockInternes' => $stockInternes,
        ));
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

        $stockInternes = $this->getDoctrine()
	    		->getRepository('AppBundle:StockInterneGeneral')
	            ->findBy(array(
	            	'agence' => $agence
	            ));

        return $this->render('StockInterneGeneralBundle:EntreeSortie:add.html.twig',array(
        	'agence' => $agence,
            'stockInternes' => $stockInternes,
        ));
    }

    public function saveAction(Request $request)
    {
        $montant_total = $request->request->get('montant_total');
        $date = $request->request->get('date');
        $date = \DateTime::createFromFormat('j/m/Y', $date);

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $entree = new EntreeSortieStockInterneGeneral();

        $entree->setDate($date);
        $entree->setTotal($montant_total);
        $entree->setAgence($agence);
        $entree->setType(1);

        $em = $this->getDoctrine()->getManager();
        $em->persist($entree);
        $em->flush();
        
        $stock_interne_general_list = $request->request->get('stock_interne_general');
        $qte_list = $request->request->get('qte');
        $portion_list = $request->request->get('portion');
        $prix_list = $request->request->get('prix');
        $total_list = $request->request->get('total');

        foreach ($stock_interne_general_list as $key => $value) {
            $stock_interne_general = $stock_interne_general_list[$key];
            $qte = $qte_list[$key];
            $portion = $portion_list[$key];
            $prix = $prix_list[$key];
            $total = $total_list[$key];

            $stockInterne = $this->getDoctrine()
                    ->getRepository('AppBundle:StockInterneGeneral')
                    ->find($stock_interne_general);

            $entreeDetails = new EntreeSortieStockInterneGeneralDetails();

            $entreeDetails->setType(1);
            $entreeDetails->setDate($date);
            $entreeDetails->setQte($qte);
            $entreeDetails->setPortion($portion);
            $entreeDetails->setPrix($prix);
            $entreeDetails->setTotal($prix * $qte);
            $entreeDetails->setDescription(' Ajout stock interne :  ' . $stockInterne->getNom() . ' le ' . $date->format('d/m/Y') . ' ('. $qte . ' ' . $stockInterne->getUnite() .')' );
            $entreeDetails->setStockInterneGeneral($stockInterne);
            $entreeDetails->setEntreeSortieStockInterneGeneral($entree);

            $em->persist($entreeDetails);
            $em->flush();

            // 
            $stockInterne->setStock( $stockInterne->getStock() + $qte );
            $stockInterne->setPortion( $stockInterne->getPortion() + $portion );

            $em->persist($stockInterne);
            $em->flush();
        }

        return new JsonResponse(array(
            'id' => $entree->getId()
        ));
    }

    public function addSortieAction()
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $stockInternes = $this->getDoctrine()
                ->getRepository('AppBundle:StockInterneGeneral')
                ->findBy(array(
                    'agence' => $agence
                ));

        return $this->render('StockInterneGeneralBundle:EntreeSortie:add-sortie.html.twig',array(
            'stockInternes' => $stockInternes
        ));
    }

    public function saveSortieAction(Request $request)
    {
        $date = $request->request->get('date');
        $date = \DateTime::createFromFormat('j/m/Y', $date);

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $sortie = new EntreeSortieStockInterneGeneral();

        $sortie->setDate($date);
        $sortie->setAgence($agence);
        $sortie->setType(2);

        $em = $this->getDoctrine()->getManager();
        $em->persist($sortie);
        $em->flush();
        
        $stock_interne_general_list = $request->request->get('stock_interne_general');
        $portion_list = $request->request->get('portion');

        foreach ($stock_interne_general_list as $key => $value) {
            $stock_interne_general = $stock_interne_general_list[$key];
            $portion = $portion_list[$key];

            $stockInterne = $this->getDoctrine()
                    ->getRepository('AppBundle:StockInterneGeneral')
                    ->find($stock_interne_general);

            $sortieDetails = new EntreeSortieStockInterneGeneralDetails();

            $sortieDetails->setDate($date);
            $sortieDetails->setPortion($portion);
            $sortieDetails->setDescription(' Sortie stock interne :  ' . $stockInterne->getNom() . ' le ' . $date->format('d/m/Y') . ' ('. $portion . ' portions)' );
            $sortieDetails->setStockInterneGeneral($stockInterne);
            $sortieDetails->setEntreeSortieStockInterneGeneral($sortie);
            $sortieDetails->setType(2);

            $em->persist($sortieDetails);
            $em->flush();

            // 
            // $stockInterne->setStock( $stockInterne->getStock() + $qte );
            $stockInterne->setPortion( $stockInterne->getPortion() - $portion );

            $em->persist($stockInterne);
            $em->flush();
        }

        return new JsonResponse(array(
            'id' => $sortie->getId()
        ));
    }

    public function listAction(Request $request)
    {
        $stock_interne_general = $request->request->get('stock_interne_general');
        $type = $request->request->get('type');
        $type_date = $request->request->get('type_date');
        $mois = $request->request->get('mois');
        $annee = $request->request->get('annee');
        $date_specifique = $request->request->get('date_specifique');
        $debut_date = $request->request->get('debut_date');
        $fin_date = $request->request->get('fin_date');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $details = $this->getDoctrine()
                ->getRepository('AppBundle:EntreeSortieStockInterneGeneralDetails')
                ->consultation(
                    $agence->getId(),
                    $stock_interne_general,
                    $type,
                    $type_date,
                    $mois,
                    $annee,
                    $date_specifique,
                    $debut_date,
                    $fin_date
                );

        return $this->render('StockInterneGeneralBundle:EntreeSortie:list.html.twig',array(
            'details' => $details,
        ));

    }
}
