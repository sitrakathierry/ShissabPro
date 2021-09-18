<?php

namespace MenuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ClientMaladieController extends Controller
{
	public function indexAction()
    {

    	$id = $_GET['id'];

    	$assurance = $this->getDoctrine()
            ->getRepository('AppBundle:AssuranceMaladie')
            ->find($id);

        $nomSociete = $assurance->getClient()->getIdClientMorale()->getNomSociete();
        $email = $assurance->getClient()->getIdClientMorale()->getEmail();

        return $this->render('MenuBundle:ClientMaladie:menu-left.html.twig',array(
        	'id' => $id,
        	'nomSociete' => $nomSociete,
        	'email' => $email
        ));
    }
}
