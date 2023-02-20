<?php 

namespace FactureBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
	public function checkFactureProduit()
	{

		$routes = array(
			'facture_homepage',
			'produit_homepage',
		);

        return $this->check($routes);
	}

	public function checkFactureService()
	{

		$routes = array(
			'facture_homepage',
			'service_homepage',
		);

        return $this->check($routes);
	}

	public function checkFactureCaisse()
	{

		$routes = array(
			'facture_homepage',
			'caisse_homepage',
		);

        return $this->check($routes);
	}

	public function checkFactureBonCommande()
	{

		$routes = array(
			'facture_homepage',
			'bon_commande_homepage',
		);

        return $this->check($routes);
	}

	public function checkFactureHebergement()
	{

		$routes = array(
			'facture_homepage',
			'hebergement_homepage',
		);

        return $this->check($routes);
	}

	public function checkFactureRestaurant()
	{

		$routes = array(
			'facture_homepage',
			'restaurant_homepage',
		);

        return $this->check($routes);
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