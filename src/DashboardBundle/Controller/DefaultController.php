<?php

namespace DashboardBundle\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {

        $checkClient = $this->check(['client_dashboard']);
        $checkFacture = $this->check(['facture_homepage']);
        $checkProduit = $this->check(['produit_homepage']);
        $checkCaisse = $this->check(['caisse_homepage']);
        $checkBonCommande = $this->check(['bon_commande_homepage']);
        $checkCredit = $this->check(['credit_homepage']);
        $checkHeb = $this->check(['hebergement_homepage']);
        $checkResto = $this->check(['restaurant_homepage']);

        return $this->render('DashboardBundle:Default:index.html.twig', array(
            'checkClient' => $checkClient,
            'checkFacture' => $checkFacture,
            'checkProduit' => $checkProduit,
            'checkCaisse' => $checkCaisse,
            'checkBonCommande' => $checkBonCommande,
            'checkCredit' => $checkCredit,
            'checkHeb' => $checkHeb,
            'checkResto' => $checkResto,
        ));
    }

    public function check($routes)
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();


        foreach ($routes as $route) {

            $menu = $this->getDoctrine()
                        ->getRepository('AppBundle:Menu')
                        ->findOneBy(array(
                            'route' => $route
                        ));

            if (!$menu) {
                return false;
            }

            $menuParAgence = $this->getDoctrine()
                        ->getRepository('AppBundle:MenuParAgence')
                        ->findOneBy(array(
                            'menu' => $menu,
                            'agence' => $agence,
                        ));

            if (!$menuParAgence) {
                return false;
            }
        }

        return true;
    }

}
