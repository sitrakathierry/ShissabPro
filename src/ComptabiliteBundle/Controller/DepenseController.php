<?php

namespace ComptabiliteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DepenseController extends Controller
{
	public function indexAction()
    {
        return $this->render('ComptabiliteBundle:Depense:index.html.twig');
    }
}
