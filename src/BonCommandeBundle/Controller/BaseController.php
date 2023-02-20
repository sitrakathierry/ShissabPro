<?php 

namespace BonCommandeBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
	public function checkBonCommandeProduit()
	{

		$routes = array(
			'bon_commande_homepage',
			'produit_homepage',
		);

        return $this->check($routes);
	}

	public function checkBonCommandeService()
	{

		$routes = array(
			'bon_commande_homepage',
			'service_homepage',
		);

        return $this->check($routes);
	}

	public function checkBonCommandeBonLivraison()
	{

		$routes = array(
			'bon_commande_homepage',
			'bon_livraison_homepage',
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