<?php

namespace ComptabiliteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Cheque;

class ChequeSortantController extends Controller
{

	public function indexAction()
    {
        return $this->render('ComptabiliteBundle:ChequeSortant:index.html.twig');
    }

    public function addAction()
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

        return $this->render('ComptabiliteBundle:ChequeSortant:add.html.twig',array(
            'agence' => $agence,
            'banques' => $banques,
        ));
    }

    public function saveAction(Request $request)
    {
        $id = $request->request->get('id');
        $num = $request->request->get('cheque');
        $montant = $request->request->get('montant');
        $raison = $request->request->get('raison');
        $date = $request->request->get('date');
        $lettre = $request->request->get('lettre');
        $devise = $request->request->get('devise');
        $date_cheque = $request->request->get('date_cheque');
        $banque = $request->request->get('banque');
        $statut = $request->request->get('statut');
        $type = $request->request->get('type');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $banque = $this->getDoctrine()
                    ->getRepository('AppBundle:Banque')
                    ->find($banque);
        if ($id) {
            $cheque = $this->getDoctrine()
                    ->getRepository('AppBundle:Cheque')
                    ->find($id);
        } else {
            $cheque = new Cheque();
        }

        $cheque->setNum($num);
        $cheque->setMontant($montant);
        $cheque->setRaison($raison);
        $cheque->setDate( \DateTime::createFromFormat('j/m/Y', $date) );
        $cheque->setLettre($lettre);
        $cheque->setDevis($devise);
        $cheque->setDateCheque( \DateTime::createFromFormat('j/m/Y', $date_cheque) );
        $cheque->setBanque($banque);
        $cheque->setType($type);
        $cheque->setStatut($statut);
        $cheque->setAgence($agence);

        $em = $this->getDoctrine()->getManager();
        $em->persist($cheque);
        $em->flush();

        return new Response(200);
    }

    public function encoursAction()
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

        return $this->render('ComptabiliteBundle:ChequeSortant:encours.html.twig',array(
            'banques' => $banques
        ));
    }

    public function listAction(Request $request)
    {

        $statut = $request->request->get('statut');
        $type = $request->request->get('type');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();


        $cheques = $this->getDoctrine()
                    ->getRepository('AppBundle:Cheque')
                    ->list(
                        $agence->getId(),
                        $type,
                        $statut
                    );

        return new JsonResponse($cheques);
    }

    public function showAction($id)
    {
        $cheque = $this->getDoctrine()
                    ->getRepository('AppBundle:Cheque')
                    ->find($id);

        $agence = $cheque->getAgence();

        $banques = $this->getDoctrine()
                    ->getRepository('AppBundle:Banque')
                    ->findBy(array(
                        'agence' => $agence
                    ));

        return $this->render('ComptabiliteBundle:ChequeSortant:show.html.twig',array(
            'agence' => $agence,
            'banques' => $banques,
            'cheque' => $cheque,
        ));
    }

    public function validationAction($id)
    {
        $cheque = $this->getDoctrine()
                    ->getRepository('AppBundle:Cheque')
                    ->find($id);

        $cheque->setStatut(1);

        $em = $this->getDoctrine()->getManager();
        $em->persist($cheque);
        $em->flush();

        return new Response(200);

    }

    public function valideAction()
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

        return $this->render('ComptabiliteBundle:ChequeSortant:valide.html.twig',array(
            'banques' => $banques
        ));
    }

}
