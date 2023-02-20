<?php

namespace Api\SitewebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return new JsonResponse(array(
            'name' => 'API Shissab V1',
            'version' => '1.0',
            'ressources' => [
                'api/siteweb' => [
                    '/details/{id_siteweb}' => 'Details',
                    '/apropos/{id_siteweb}' => 'A Propos',
                    '/sliders/{id_siteweb}' => 'Liste des sliders',
                ]
            ]
        ));
    }
}
