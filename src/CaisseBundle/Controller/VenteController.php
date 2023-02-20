<?php

namespace CaisseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Commande;
use AppBundle\Entity\Pannier;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class VenteController extends Controller
{
	public function addAction()
    {

    	$user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $produits  = $this->getDoctrine()
            ->getRepository('AppBundle:Produit')
            ->getList($agence->getId());

        // $variations = $this->getDoctrine()
        // 		->getRepository('AppBundle:VariationProduit')
        //         ->list($agence->getId());

        for ($i = 0; $i < count($produits); $i++) {
            $totalStock = $this->getDoctrine()
                ->getRepository('AppBundle:VariationProduit')
            ->getTotalVariationProduit($agence->getId(), $produits[$i]["id"]);
            $produits[$i]["stock"] = number_format($totalStock["stockG"], 0, ".", " ");

            if (empty($produits[$i]["stock"])) {
                $produits[$i]["stock"] = 0;
            }
        }
        
        return $this->render('CaisseBundle:Vente:add.html.twig', array(
        	'agence' => $agence,
            // 'variations' => $variations,
            'produits' => $produits
        ));
    }

    public function saveAction(Request $request)
    {
    	$id = $request->request->get('id');
    	$montant_total = $request->request->get('montant_total');
    	$date = $request->request->get('date');
        $date = \DateTime::createFromFormat('j/m/Y', $date);
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        if ($id) {
        	$commande = $this->getDoctrine()
	    		->getRepository('AppBundle:Commande')
	            ->find($id);
        } else {
        	$commande = new Commande();
        }

        $commande->setTotal($montant_total);
        $commande->setDate($date);
        $commande->setAgence($agence);

        $em = $this->getDoctrine()->getManager();
        $em->persist($commande);
        $em->flush();

        // $produitList = $request->request->get('produit');
        $qteList = $request->request->get('qte');
        $prixList = $request->request->get('prix');
        $variationList = $request->request->get('variation');
        $totalList = $request->request->get('total');

        if (!empty($variationList)) {
            foreach ($variationList as $key => $value) {

        		$panier = new Pannier();

                $qte = $qteList[$key];
                $prix = $prixList[$key];
                $total = $totalList[$key];
                $variation = $variationList[$key];

        		$variation = $this->getDoctrine()
                		    		->getRepository('AppBundle:VariationProduit')
                		            ->find( $variation );
                
                $produitEntrepot = $variation->getProduitEntrepot(); 

                $produit = $produitEntrepot->getProduit(); 

        		$panier->setDate($date);
        		$panier->setQte($qte);
        		$panier->setPu($prix);
        		$panier->setTotal($total);
        		$panier->setVariationProduit($variation);
        		$panier->setCommande($commande);

        		$em->persist($panier);
        		$em->flush();

                /**
                 * Stock produit
                 */
                $produit->setStock( $produit->getStock() - $qte );
                $em->persist($produit);
                $em->flush();

                /**
                 * Stock produitEntrepot
                 */
                $produitEntrepot->setStock( $produitEntrepot->getStock() - $qte );
                $em->persist($produitEntrepot);
                $em->flush();

                /**
                 * Stock variation
                 */
                $variation->setStock( $variation->getStock() - $qte );
                $em->persist($variation);
                $em->flush();

                // $produit->setStock( $produit->getStock() - $qte );
        		// $prixProduit->setStock( $prixProduit->getStock() - $qte );
        		// $em->persist($produit);
        	
        	}
        }

        return new JsonResponse(array(
        	'id' => $commande->getId()
        ));
    }

    public function consultationAction()
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $produits = $this->getDoctrine()
                ->getRepository('AppBundle:Produit')
                ->findBy(array(
                    'agence' => $agence
                ));

        return $this->render('CaisseBundle:Vente:consultation.html.twig',array(
            'userAgence' => $userAgence,
            'produits' => $produits,
        ));
    }

    public function listAction(Request $request)
    {

        $agence = $request->request->get('agence');
        $produit_id = $request->request->get('produit');
        $type_date = $request->request->get('type_date');
        $mois = $request->request->get('mois');
        $annee = $request->request->get('annee');
        $date_specifique = $request->request->get('date_specifique');
        $debut_date = $request->request->get('debut_date');
        $fin_date = $request->request->get('fin_date');

        $commandes = $this->getDoctrine()
	                    ->getRepository('AppBundle:Commande')
	                    ->consultation(
                            $agence,
                            0,
                            $type_date,
                            $mois,
                            $annee,
                            $date_specifique,
                            $debut_date,
                            $fin_date
                        );

        $data = array();

        foreach ($commandes as $commande) {
            $panniers = $this->getDoctrine()
                    ->getRepository('AppBundle:Pannier')
                    ->consultation($commande['id']);


            $commande['panniers'] = null;

            if (!empty($panniers)) {
                $panniers = $this->produitFilter($panniers, $produit_id);
                $commande['panniers'] = $panniers;
            }

            array_push($data, $commande);
        }

        $agence = $this->getDoctrine()
                        ->getRepository('AppBundle:Agence')
                        ->find($agence);

        return $this->render('CaisseBundle:Vente:list.html.twig',array(
            'agence' => $agence,
            'commandes' => $data,
        ));
        
    }

    public function produitFilter($panniers, $produit_id = 0)
    {

        if (!$produit_id) {
            return $panniers;
        }

        foreach ($panniers as $pannier) {
            if ($pannier['produit_id'] == $produit_id) {
                return $panniers;
            }
        }

        return [];
    }

    public function str16Format($str)
    {
        $split = str_split($str, 16);
        return implode("\n", $split);
    }

    public function showAction($id)
    {

        $commande = $this->getDoctrine()
                        ->getRepository('AppBundle:Commande')
                        ->find($id);

        $panniers = $this->getDoctrine()
                    ->getRepository('AppBundle:Pannier')
                    ->findBy(array(
                        'commande' => $commande
                    ));

        $agence = $commande->getAgence();

        return $this->render('CaisseBundle:Vente:show.html.twig',array(
            'agence' => $agence,
            'commande' => $commande,
            'panniers' => $panniers,
        ));
    }

    public function deleteAction($id)
    {
        $commande = $this->getDoctrine()
                        ->getRepository('AppBundle:Commande')
                        ->find($id);

        $panniers = $this->getDoctrine()
                        ->getRepository('AppBundle:Pannier')
                        ->findBy(array(
                            'commande' => $commande
                        ));

        $em = $this->getDoctrine()->getManager();
        
        foreach ($panniers as $pannier) {
            $variation = $pannier->getVariationProduit();
            $produitEntrepot = $variation->getProduitEntrepot(); 
            $produit = $produitEntrepot->getProduit(); 
            $qte = $pannier->getQte(); 

            /**
             * Stock produit
             */
            $produit->setStock( $produit->getStock() + $qte );
            $em->persist($produit);
            $em->flush();

            /**
             * Stock produitEntrepot
             */
            $produitEntrepot->setStock( $produitEntrepot->getStock() + $qte );
            $em->persist($produitEntrepot);
            $em->flush();

            /**
             * Stock variation
             */
            $variation->setStock( $variation->getStock() + $qte );
            $em->persist($variation);
            $em->flush();

        }

        $em->remove($commande);
        $em->flush();

        return new JsonResponse(200);
        
    }

    public function printTicketAction(Request $request)
    {
        $commande_id = $request->request->get('commande_id');
        $printer_name = $request->request->get('printer_name');

        $commande = $this->getDoctrine()
                ->getRepository('AppBundle:Commande')
                ->find($commande_id);


        $data = $this->makeTicketData($commande);

        return new JsonResponse(array(
            'data' => $data
        ));

    }


    public function makeTicketData($commande)
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $data = array(
            'agence' => $agence->getNom(),
            'adresse' => $agence->getAdresse() ? $agence->getAdresse() : $agence->getRegion(),
            'tel' => $agence->getTel(),
            'recu' => "RECU N° " . $commande->getRecu(),
            'qrcode' => $commande->getRecu(),
            'date' => "Le ". $commande->getDate()->format('d/m/Y'),
            'thead' => ["Designation","Qte","Total"],
            'tbody' => [],
            'tfoot' => ["Total","",round($commande->getTotal(), 2)],
            'caissier' => [ "Caissier :",$user->getUsername() ],
        );

        $panniers = $this->getDoctrine()
                    ->getRepository('AppBundle:Pannier')
                    ->findBy(array(
                        'commande' => $commande
                    ));


        foreach ($panniers as $pannier) {
            $designation = $pannier->getVariationProduit()->getProduitEntrepot()->getProduit()->getNom();

            $designation = str_split($designation, 16);

            if (count($designation) > 1) {
                for ($i=0; $i < count($designation); $i++) { 

                    $value = $designation[$i];

                    if ($i == count($designation) - 1) {
                        $trlast = [ $designation[$i] , " " . $pannier->getQte() ,  round($pannier->getTotal(), 2) ];
                        array_push($data['tbody'], $trlast);

                    } else {
                        array_push($data['tbody'], [$value . " "]);
                    }
                    
                }
                
            } else {
                $tr = [ $designation[0] , " " . $pannier->getQte() ,  round($pannier->getTotal(), 2) ];
                array_push($data['tbody'], $tr);
            }


        }


        return $data;

    }

    function addSpaces($string = '', $valid_string_length = 0) {
        if (strlen($string) < $valid_string_length) {
            $spaces = $valid_string_length - strlen($string);
            for ($index1 = 1; $index1 <= $spaces; $index1++) {
                $string = $string . ' ';
            }
        }

        return $string;
    }

}
