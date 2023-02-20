<?php 

namespace HebergementBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
	
	public function checkHebergement()
	{

		$routes = array(
			'hebergement_homepage',
		);

        return $this->check($routes);
		
	}

	public function hebergementRestaurantRelation()
	{

		$routes = array(
			'hebergement_homepage',
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