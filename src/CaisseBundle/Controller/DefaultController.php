<?php

namespace CaisseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CaisseBundle:Default:index.html.twig');
    }
}
