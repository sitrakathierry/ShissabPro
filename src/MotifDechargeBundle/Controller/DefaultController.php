<?php

namespace MotifDechargeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MotifDechargeBundle:Default:index.html.twig');
    }
}
