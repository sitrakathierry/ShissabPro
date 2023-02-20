<?php

namespace ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
	public function indexAction()
    {

        $totalProduits = $this->totalProduits();
        $totalEntrepots = $this->totalEntrepots();
        $totalProduitEntrepot = $this->totalProduitEntrepot();
    	$ventes = $this->totalVentes();
        $inventaires = $this->totalInventaires();

    	// $achats = $this->totalAchacts() + $this->totalAchactsStockInterne();

    	// $depenses = $this->totalCharges();


        return $this->render('ProduitBundle:Dashboard:index.html.twig',array(
        	'totalProduits' => $totalProduits,
            'totalEntrepots' => $totalEntrepots,
            'totalProduitEntrepot' => $totalProduitEntrepot,
            'ventes' => $ventes,
            'inventaires' => $inventaires,
        ));
    }

    public function totalInventaires()
    {

        $user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $agence = $userAgence->getAgence();

        $inventaires  = $this->getDoctrine()
                        ->getRepository('AppBundle:VariationProduit')
                        ->list($agence->getId());

        if (!empty($inventaires)) {
            return array_sum( array_column($inventaires, 'total') );
        }

        return 0;
    }

    public function totalProduitEntrepot()
    {
        $user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $agence = $userAgence->getAgence();
        

        $produits  = $this->getDoctrine()
                        ->getRepository('AppBundle:ProduitEntrepot')
                        ->getList($agence->getId());

        return count($produits);
    }

    public function totalEntrepots()
    {
        $user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $agence = $userAgence->getAgence();

        $entrepots = $this->getDoctrine()
                    ->getRepository('AppBundle:Entrepot')
                    ->findBy(array(
                        'agence' => $agence
                    ));

        return count($entrepots);
    }

    public function totalProduits()
    {
        $user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $agence = $userAgence->getAgence();

        $produits  = $this->getDoctrine()
                        ->getRepository('AppBundle:Produit')
                        ->getList($agence->getId());

        return count($produits);
    }

    public function totalAchactsStockInterne()
    {
        $user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $agence = $userAgence->getAgence();

        $achats = $this->getDoctrine()
                ->getRepository('AppBundle:EntreeSortieStockInterneGeneralDetails')
                ->consultation(
                    $agence->getId(),
                    false,
                    1
                );

        if (!empty($achats)) {
            return array_sum( array_column($achats, 'total') );
        }

        return 0;
    }

    public function totalAchacts()
    {
    	$user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $agence = $userAgence->getAgence();

        $role = $user->getRoles()[0];

        $id_entrepot = false;

        if ($role != "ROLE_RESPONSABLE") {
        	$userEntrepot = $this->getDoctrine()
                    ->getRepository('AppBundle:UserEntrepot')
                    ->findOneBy(array(
                        'user' => $user
                    ));

            $id_entrepot = $userEntrepot->getEntrepot()->getId();

        }

    	$achats = $this->getDoctrine()
                ->getRepository('AppBundle:Approvisionnement')
                ->entreesSorties(
                    0, 
                    1,
                    null,
                    $id_entrepot,
                    $agence->getId()
                );

        if (!empty($achats)) {
        	return array_sum( array_column($achats, 'total') ) - array_sum( array_column($achats, 'charge') );
        }

        return 0;

    }

    public function totalCharges()
    {
    	$user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $agence = $userAgence->getAgence();

        $role = $user->getRoles()[0];

        $id_entrepot = false;

        if ($role != "ROLE_RESPONSABLE") {
        	$userEntrepot = $this->getDoctrine()
                    ->getRepository('AppBundle:UserEntrepot')
                    ->findOneBy(array(
                        'user' => $user
                    ));

            $id_entrepot = $userEntrepot->getEntrepot()->getId();

        }

    	$achats = $this->getDoctrine()
                ->getRepository('AppBundle:Approvisionnement')
                ->entreesSorties(
                    0, 
                    1,
                    null,
                    $id_entrepot,
                    $agence->getId()
                );

        if (!empty($achats)) {
        	return array_sum( array_column($achats, 'charge') );
        }

        return 0;

    }

    public function totalVentes()
    {
    	$user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $agence = $userAgence->getAgence();

        $role = $user->getRoles()[0];

        $id_entrepot = false;

        if ($role != "ROLE_RESPONSABLE") {
        	$userEntrepot = $this->getDoctrine()
                    ->getRepository('AppBundle:UserEntrepot')
                    ->findOneBy(array(
                        'user' => $user
                    ));

            $id_entrepot = $userEntrepot->getEntrepot()->getId();

        }
        
    	$ventes = $this->getDoctrine()
                ->getRepository('AppBundle:Approvisionnement')
                ->entreesSorties(
                    0, 
                    2,
                    null,
                    $id_entrepot,
                    $agence->getId()
                );

        if (!empty($ventes)) {
        	return array_sum( array_column($ventes, 'total') );
        }

        return 0;

    }
}
