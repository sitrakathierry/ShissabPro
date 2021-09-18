<?php

namespace DashboardBundle\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render('DashboardBundle:Default:index.html.twig');
    }

}
