<?php

namespace AgenceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Devise;

class ParametreController extends BaseController
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
        $checkProduit = $this->checkProduit();

        $devises = $this->getDoctrine()
                    ->getRepository('AppBundle:Devise')
                    ->findBy(array(
                        'agence' => $agence
                    ));

        $entrepots = $this->getDoctrine()
                ->getRepository('AppBundle:Entrepot')
                ->findBy(array(
                    'agence' => $agence
                ));

        return $this->render('AgenceBundle:Parametre:index.html.twig',array(
            'agence' => $agence,
            'entrepots' => $entrepots,
            'devises' => $devises,
            'checkProduit' => $checkProduit,
        ));
    }

    public function saveAction(Request $request)
    {
        $agence_id = $request->request->get('agence_id');
        $devise_symbole = $request->request->get('devise_symbole');
        $devise_lettre = $request->request->get('devise_lettre');

        $agence = $this->getDoctrine()
                    ->getRepository('AppBundle:Agence')
                    ->find($agence_id);

        $agence->setDeviseSymbole($devise_symbole);
        $agence->setDeviseLettre($devise_lettre);

        $em = $this->getDoctrine()->getManager();
        $em->persist($agence);
        $em->flush();

        return new JsonResponse(array(
            'agence_id' => $agence_id
        ));
        
    }

    public function saveDeviseAction(Request $request)
    {
        $id = $request->request->get('id');
        $symbole = $request->request->get('symbole');
        $lettre = $request->request->get('lettre');
        $montant_principal = $request->request->get('montant_principal');
        $montant_conversion = $request->request->get('montant_conversion');
        $agence_id = $request->request->get('agence_id');

        $agence = $this->getDoctrine()
                    ->getRepository('AppBundle:Agence')
                    ->find($agence_id);

        if ($id) {
            $devise = $this->getDoctrine()
                    ->getRepository('AppBundle:Devise')
                    ->find($id);
        } else {
            $devise = new Devise();
        }

        $devise->setSymbole($symbole);
        $devise->setLettre($lettre);
        $devise->setSymbole($symbole);
        $devise->setMontantPrincipal($montant_principal);
        $devise->setMontantConversion($montant_conversion);
        $devise->setAgence($agence);

        $em = $this->getDoctrine()->getManager();
        $em->persist($devise);
        $em->flush();

        return new JsonResponse(array(
            'id' => $devise->getId()
        ));

    }

    public function deleteDeviseAction($id)
    {
        $devise  = $this->getDoctrine()
                        ->getRepository('AppBundle:Devise')
                        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($devise);
        $em->flush();

        return new JsonResponse(200);
    }

    public function saveDeviseEntrepotAction(Request $request)
    {
        $agence_id = $request->request->get('agence_id');
        $devises = $request->request->get('devises');

        $em = $this->getDoctrine()->getManager();

        foreach ($devises as $item) {
            $entrepot  = $this->getDoctrine()
                        ->getRepository('AppBundle:Entrepot')
                        ->find($item['entrepot']);

            $entrepot->setDeviseSymbole( $item['devise_symbole'] );
            $entrepot->setDeviseLettre( $item['devise_lettre'] );
            
            $em->persist($entrepot);
            $em->flush();

        }

        return new JsonResponse(200);

    }

    public function saveTicketAction(Request $request)
    {
        $agence_id = $request->request->get('agence_id');
        $ticket_titre = $request->request->get('ticket_titre');
        $ticket_soustitre = $request->request->get('ticket_soustitre');
        $ticket_adresse = $request->request->get('ticket_adresse');
        $ticket_tel = $request->request->get('ticket_tel');

        $agence = $this->getDoctrine()
                    ->getRepository('AppBundle:Agence')
                    ->find($agence_id);

        $agence->setTitreTicket($ticket_titre);
        $agence->setSoustitreTicket($ticket_soustitre);
        $agence->setAdresseTicket($ticket_adresse);
        $agence->setTelTicket($ticket_tel);

        $em = $this->getDoctrine()->getManager();
        $em->persist($agence);
        $em->flush();

        return new JsonResponse(array(
            'agence_id' => $agence_id
        ));
        
    }
}
