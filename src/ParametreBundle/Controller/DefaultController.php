<?php

namespace ParametreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ParametreBundle:Default:index.html.twig');
    }

    public function ficheClientAction()
    {
        return $this->render('ParametreBundle:Default:fiche-client.html.twig');
    }

    public function typeSocieteAction()
    {
        return $this->render('ParametreBundle:Default:type-societe.html.twig');
    }

    public function typeSocialAction()
    {
        return $this->render('ParametreBundle:Default:type-social.html.twig');
    }
}
