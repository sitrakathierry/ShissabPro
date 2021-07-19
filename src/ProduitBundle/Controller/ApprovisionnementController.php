<?php

namespace ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ApprovisionnementController extends Controller
{
	public function addAction()
    {
        return $this->render('ProduitBundle:Approvisionnement:add.html.twig');
    }
}
