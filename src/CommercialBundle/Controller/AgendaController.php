<?php

namespace CommercialBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AgendaController extends Controller
{
	public function indexAction()
    {
        return $this->render('CommercialBundle:Agenda:index.html.twig');
    }
}
