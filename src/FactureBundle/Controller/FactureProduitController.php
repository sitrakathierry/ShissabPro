<?php

namespace FactureBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Facture;
use AppBundle\Entity\FactureProduit;
use AppBundle\Entity\FactureProduitDetails;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FactureProduitController extends Controller
{
    public function saveAction(Request $request)
    {
        $f_type = $request->request->get('f_type');
        $f_model = $request->request->get('f_model');
        $f_client = $request->request->get('f_client');
        $f_date = $request->request->get('f_date');
        $descr = $request->request->get('descr');

        $montant = $request->request->get('montant');
        $f_remise = $request->request->get('f_remise');
        $remise = $request->request->get('remise');
        $total = $request->request->get('total');
        $somme = $request->request->get('somme');

        $f_id = $request->request->get('f_id');
        $list_id = $request->request->get('list_id');

        $client = $this->getDoctrine()
            ->getRepository('AppBundle:Client')
            ->find($f_client);

        $agence = $client->getAgence();

        if ($f_id) {
            $facture = $this->getDoctrine()
                ->getRepository('AppBundle:Facture')
                ->find($f_id);
             $newNum = str_pad($facture->getNum(), 3, '0', STR_PAD_LEFT);

             $factureProduit = $this->getDoctrine()
                ->getRepository('AppBundle:FactureProduit')
                ->findOneBy(array(
                    'facture' => $facture
                ));
        } else{
            $facture = new Facture();
            $newNum = $this->prepareNewNumFacture($agence->getId());
            $facture->setNum(intval($newNum));

            $factureProduit = new FactureProduit();

        }

        $facture->setType($f_type);
        $facture->setModele($f_model);
        $facture->setMontant($montant);
        $facture->setRemisePourcentage($f_remise);
        $facture->setRemiseValeur($remise);
        $facture->setTotal($total);
        $facture->setSomme($somme);
        $facture->setDescr($descr);
        $facture->setClient($client);

        $dateCreation = new \DateTime('now');
        $facture->setDateCreation($dateCreation);
        
        $date = \DateTime::createFromFormat('j/m/Y', $f_date);

        $facture->setDate($date);

        $facture->setAgence($agence);

        $em = $this->getDoctrine()->getManager();
        $em->persist($facture);
        $em->flush();

        $factureProduit->setFacture($facture);

        $em->persist($factureProduit);
        $em->flush();

        $details_list = explode(",", $list_id);

        // Suppression de tous les details
        foreach ($details_list as $old_id) {

            if ($old_id != "") {
                $old_detail = $this->getDoctrine()
                                    ->getRepository('AppBundle:FactureProduitDetails')
                                    ->find($old_id);

                $em->remove($old_detail);
                $em->flush();
            }

        }

        $f_produit = $request->request->get('f_produit');
        $f_prix = $request->request->get('f_prix');
        $f_qte = $request->request->get('f_qte');
        $f_montant = $request->request->get('f_montant');

        if (!empty($f_produit)) {
            foreach ($f_produit as $key => $value) {
                $detail = new FactureProduitDetails();
                $produit = $this->getDoctrine()
                    ->getRepository('AppBundle:Produit')
                    ->find( $f_produit[$key] );

                $prix = $f_prix[$key];
                $qte = $f_qte[$key];
                $montant = $f_montant[$key];

                $detail->setProduit($produit);
                $detail->setPrix($prix);
                $detail->setQte($qte);
                $detail->setMontant($montant);
                $detail->setFactureProduit($factureProduit);

                // var_dump("expression");die();

                $em->persist($detail);
                $em->flush();

            }
        }

        return $this->redirectToRoute('facture_show',array('id' => $facture->getId()));

    }

    public function prepareNewNumFacture($id_agence)
    {
        $em = $this->getDoctrine()
                ->getManager();
        $newNum = $em
            ->getRepository("AppBundle:Facture")
            ->newNum($id_agence);

        return $newNum;

    }
}
