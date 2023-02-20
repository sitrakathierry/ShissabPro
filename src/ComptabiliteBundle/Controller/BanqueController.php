<?php

namespace ComptabiliteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Banque;
use AppBundle\Functions\GoogleCalendar;

class BanqueController extends Controller
{
	public function indexAction()
    {
        return $this->render('ComptabiliteBundle:Banque:index.html.twig');
    }

    public function listAction()
    {

        // $calendar = new GoogleCalendar();

        // $client = $calendar->getCalendar();

        return $this->render('ComptabiliteBundle:Banque:list.html.twig');
    }

    public function getListAction(Request $request)
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
                ->list($agence->getId());

        return new JsonResponse($banques);
    }

    public function saveAction(Request $request)
    {
    	$nom = $request->request->get('nom');
        $id = $request->request->get('id');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        if ($id) {
        	$banque = $this->getDoctrine()
                    ->getRepository('AppBundle:Banque')
                    ->find($id);
        } else {
        	$banque = new Banque();
        }

        $banque->setNom($nom);
        $banque->setAgence($agence);

        $em = $this->getDoctrine()->getManager();
        $em->persist($banque);
        $em->flush();

        if ($banque->getId()) {
        	return new Response(200);
        }
    	
    }

    public function deleteAction($id)
    {
        $banque  = $this->getDoctrine()
                        ->getRepository('AppBundle:Banque')
                        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($banque);
        $em->flush();

        return new JsonResponse(200);
        
    }

    public function editorAction(Request $request)
    {
        $id = $request->request->get('id');
        $banque = $this->getDoctrine()->getRepository('AppBundle:Banque')
            ->find($id);

        return $this->render('@Comptabilite/Banque/editor.html.twig',[
            'banque' => $banque,
        ]);
    }

    public function updateAgenceAction()
    {
        $comptes = $this->getDoctrine()
            ->getRepository('AppBundle:CompteBancaire')
            ->findAll();

        $em = $this->getDoctrine()->getManager();
        
        foreach ($comptes as $compte) {
            $banque_compte = $compte->getBanque();
            $agence_compte = $compte->getAgence();
            $agence_banque = $banque_compte->getAgence();

            if (!$agence_banque) {
                $banque_compte->setAgence($agence_compte);
                $em->persist($banque_compte);
                $em->flush();
            } else {
                if ($agence_compte != $agence_banque) {
                    $banque_agence = new Banque();

                    $banque_agence->setNom( $banque_compte->getNom() );
                    $banque_agence->setAgence( $agence_compte );

                    $em->persist($banque_agence);
                    $em->flush();

                    $compte->setBanque($banque_agence);
                    $em->persist($banque_agence);
                    $em->flush();
                }
            }


        }

        echo "succes"; die();
    }
}
