<?php

namespace ComptabiliteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ChequeController extends Controller
{
	public function indexAction()
    {
        return $this->render('ComptabiliteBundle:Cheque:index.html.twig');
    }
}
