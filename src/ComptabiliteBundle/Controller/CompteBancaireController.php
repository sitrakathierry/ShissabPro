<?php

namespace ComptabiliteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\CompteBancaire;

class CompteBancaireController extends Controller
{
	public function indexAction()
    {
    	$user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        $banques = $this->getDoctrine()
                    ->getRepository('AppBundle:Banque')
                    ->findBy(array(
                        'agence' => $agence
                    ));

        return $this->render('ComptabiliteBundle:CompteBancaire:index.html.twig',array(
        	'agence' => $agence,
            'banques' => $banques,
        ));
    }

    public function listByBanqueAction($id_banque)
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence_id = $userAgence->getAgence()->getId();

        $comptes = $this->getDoctrine()
                ->getRepository('AppBundle:CompteBancaire')
                ->listByBanque(
                    $id_banque,
                    $agence_id
                );

        return new JsonResponse($comptes);
    }

    public function getListAction(Request $request)
    {
        $recherche_par = $request->request->get('recherche_par');
        $a_rechercher = $request->request->get('a_rechercher');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence_id = $userAgence->getAgence()->getId();

        $comptes = $this->getDoctrine()
                ->getRepository('AppBundle:CompteBancaire')
                ->list(
                    $recherche_par,
                    $a_rechercher,
                    $agence_id
                );

        return new JsonResponse($comptes);
    }

    public function saveAction(Request $request)
    {
    	$banque_id = $request->request->get('banque');
    	$num_compte = $request->request->get('num_compte');
        $solde = $request->request->get('solde');
        $id = $request->request->get('id');

        if ($id) {
        	$compte = $this->getDoctrine()
                    ->getRepository('AppBundle:CompteBancaire')
                    ->find($id);
        } else {
        	$compte = new CompteBancaire();
        }

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        $banque = $this->getDoctrine()
                    ->getRepository('AppBundle:Banque')
                    ->find($banque_id);

        $compte->setBanque($banque);
        $compte->setNumCompte($num_compte);
        $compte->setSolde($solde);
        $compte->setAgence($agence);

        $em = $this->getDoctrine()->getManager();
        $em->persist($compte);
        $em->flush();

        if ($compte->getId()) {
        	return new Response(200);
        }
    	
    }

    public function deleteAction($id)
    {

        $compte  = $this->getDoctrine()
                        ->getRepository('AppBundle:CompteBancaire')
                        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($compte);
        $em->flush();

        return new JsonResponse(200);
        
    }

    public function editorAction(Request $request)
    {
        $id = $request->request->get('id');

        $compte = $this->getDoctrine()->getRepository('AppBundle:CompteBancaire')
            ->find($id);

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        $banques = $this->getDoctrine()
                    ->getRepository('AppBundle:Banque')
                    ->findBy(array(
                        'agence' => $agence
                    ));


        return $this->render('@Comptabilite/CompteBancaire/editor.html.twig',[
            'banques' => $banques,
            'compte' => $compte,
        ]);
    }
}
