<?php

namespace AppBundle\Functions;


use Symfony\Component\Routing\Router;

class RouteExists extends \Twig_Extension
{
    private $router;

    public function __construct(Router $route)
    {
        $this->router = $route;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('route_exists', array($this, 'routeExistsFilter')),
        );
    }

    public function routeExistsFilter($route)
    {
        if($route === null || $route === '') return true;
        return (null === $this->router->getRouteCollection()->get($route)) ? false : true;
    }
}