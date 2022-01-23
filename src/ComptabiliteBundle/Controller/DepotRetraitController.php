<?php

namespace ComptabiliteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Mouvement;

class DepotRetraitController extends Controller
{
    public function addMouvementAction()
    {
    	$banques = $this->getDoctrine()
                    ->getRepository('AppBundle:Banque')
                    ->findAll();
        return $this->render('ComptabiliteBundle:DepotRetrait:add-mouvement.html.twig',array(
        	'banques' => $banques,
        ));
    }

    public function saveMouvementAction(Request $request)
    {
        $id = $request->request->get('id');
        $date = $request->request->get('date');
        $operation = $request->request->get('operation');
        $num_operation = $request->request->get('num_operation');
        $type_operation = $request->request->get('type_operation');
        $compte_bancaire = $request->request->get('compte_bancaire');
        $montant = $request->request->get('montant');
        $op_nom = $request->request->get('op_nom');

        if ($id) {
            $mouvement = $this->getDoctrine()
                    ->getRepository('AppBundle:Mouvement')
                    ->find($id);
        } else {
            $mouvement = new Mouvement();
        }

        $date = \DateTime::createFromFormat('j/m/Y', $date);

        $mouvement->setDate($date);
        $mouvement->setOperation($operation);
        $mouvement->setNumOperation($num_operation);
        $mouvement->setTypeOperation($type_operation);

        $compte = $this->getDoctrine()
                    ->getRepository('AppBundle:CompteBancaire')
                    ->find($compte_bancaire);

        $mouvement->setCompteBancaire($compte);
        $mouvement->setMontant($montant);
        $mouvement->setOpNom($op_nom);

        $em = $this->getDoctrine()->getManager();
        $em->persist($mouvement);
        $em->flush();

        if ($mouvement->getId()) {
            switch ($operation) {
                case 1:
                    # dépôt...
                    $new_solde = $compte->getSolde() + intval($montant);
                    $compte->setSolde($new_solde);
                    break;
                case 2:
                    # retrait...
                    $new_solde = $compte->getSolde() - intval($montant);
                    $compte->setSolde($new_solde);
                    break;
            }

            $em->persist($compte);
            $em->flush();
        }


        return new Response(200);
        
    }

    public function soldeGeneralAction()
    {
        $banques = $this->getDoctrine()
                    ->getRepository('AppBundle:Banque')
                    ->findAll();
                    
        return $this->render('ComptabiliteBundle:DepotRetrait:solde-general.html.twig',array(
            'banques' => $banques
        ));
    }

    public function listMouvementAction(Request $request)
    {

        $banque = $request->request->get('banque');
        $compte_bancaire = $request->request->get('compte_bancaire');
        $operation = $request->request->get('operation');
        $type_date = $request->request->get('type_date');
        $mois = $request->request->get('mois');
        $annee = $request->request->get('annee');
        $date_specifique = $request->request->get('date_specifique');
        $debut_date = $request->request->get('debut_date');
        $fin_date = $request->request->get('fin_date');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence_id = $userAgence->getAgence()->getId();

        $mouvements = $this->getDoctrine()
                    ->getRepository('AppBundle:Mouvement')
                    ->list(
                        $banque,
                        $compte_bancaire,
                        $operation,
                        $type_date,
                        $mois,
                        $annee,
                        $date_specifique,
                        $debut_date,
                        $fin_date,
                        $agence_id
                    );

        return new  JsonResponse($mouvements);
    }
}
