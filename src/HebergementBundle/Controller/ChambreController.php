<?php

namespace HebergementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Chambre;
use AppBundle\Entity\TarifChambre;


class ChambreController extends Controller
{
	public function indexAction()
    {
        return $this->render('HebergementBundle:Chambre:index.html.twig');
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

        $categories = $this->getDoctrine()
                    ->getRepository('AppBundle:CategorieChambre')
                    ->findBy(array(
                        'agence' => $agence
                    ));


        return $this->render('HebergementBundle:Chambre:add.html.twig',array(
            'agence' => $agence,
            'categories' => $categories,
        ));
    }

    public function saveAction(Request $request)
    {
        $id = $request->request->get('id');
        $statut = $request->request->get('statut');
        $nom = $request->request->get('nom');
        $categorie = $request->request->get('categorie');
        $nb_lit_simple = $request->request->get('nb_lit_simple');
        $nb_lit_double = $request->request->get('nb_lit_double');
        $nb_pers_chambre = $request->request->get('nb_pers_chambre');
        $tarif_pers = $request->request->get('tarif_pers');
        $tarif_pers_petit_dejeuner = $request->request->get('tarif_pers_petit_dejeuner');
        $periode_annulation = $request->request->get('periode_annulation');
        $pourcentage_remboursement = $request->request->get('pourcentage_remboursement');
        $annulation_automatique = $request->request->get('annulation_automatique');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        if ($id) {
            $chambre = $this->getDoctrine()
                ->getRepository('AppBundle:Chambre')
                ->find($id);
        } else {
            $chambre = new Chambre();
        }

        $categorie = $this->getDoctrine()
                ->getRepository('AppBundle:CategorieChambre')
                ->find($categorie);

        $chambre->setStatut($statut);
        $chambre->setNom($nom);
        $chambre->setCategorieChambre($categorie);
        $chambre->setNbLitSimple($nb_lit_simple);
        $chambre->setNbLitDouble($nb_lit_double);
        $chambre->setNbPers($nb_pers_chambre);
        $chambre->setTarifPers($tarif_pers);
        $chambre->setTarifPersPetitDejeuner($tarif_pers_petit_dejeuner);
        $chambre->setPeriodeAnnulation($periode_annulation);
        $chambre->setPourcentageRemboursement($pourcentage_remboursement);
        $chambre->setAnnulationAutomatique($annulation_automatique);
        $chambre->setAgence($agence);
        

        $em = $this->getDoctrine()->getManager();
        $em->persist($chambre);
        $em->flush();

        $details = $this->getDoctrine()
                    ->getRepository('AppBundle:TarifChambre')
                    ->findBy(array(
                        'chambre' => $chambre
                    ));

        foreach ($details as $detail) {
            $em->remove($detail);
            $em->flush();
        }

        $nb_pers_list = $request->request->get('nb_pers');
        $montant_list = $request->request->get('montant');
        $petit_dejeuner_list = $request->request->get('petit_dejeuner');
        $montant_petit_dejeuner_list = $request->request->get('montant_petit_dejeuner');

        if (!empty($nb_pers_list)) {
            foreach ($nb_pers_list as $key => $value) {
                $nb_pers = $nb_pers_list[$key];
                $montant = $montant_list[$key];
                $petit_dejeuner = $petit_dejeuner_list[$key];
                $montant_petit_dejeuner = $montant_petit_dejeuner_list[$key];

                if ($nb_pers) {
                    $tarif = new TarifChambre();

                    $tarif->setNbPers($nb_pers);
                    $tarif->setMontant($montant);
                    $tarif->setPetitDejeuner($petit_dejeuner);
                    $tarif->setMontantPetitDejeuner($montant_petit_dejeuner);
                    $tarif->setChambre($chambre);

                    $em->persist($tarif);
                    $em->flush();
                }
            }
        }

        return new JsonResponse(array(
            'id' => $chambre->getId()
        ));

    }

    public function consultationAction()
    {
        $agences  = $this->getDoctrine()
                        ->getRepository('AppBundle:Agence')
                        ->findAll();

        $user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        return $this->render('HebergementBundle:Chambre:consultation.html.twig', array(
            'agences' => $agences,
            'userAgence' => $userAgence,
        ));
    }

    public function listAction(Request $request)
    {
        $agence = $request->request->get('agence');
        $recherche_par = $request->request->get('recherche_par');
        $a_rechercher = $request->request->get('a_rechercher');
        $categorie = $request->request->get('categorie');

        $chambres  = $this->getDoctrine()
                        ->getRepository('AppBundle:Chambre')
                        ->getList(
                            $agence,
                            $recherche_par,
                            $a_rechercher,
                            $categorie
                        );

        return new JsonResponse($chambres);
    }

    public function showAction($id)
    {

        $chambre = $this->getDoctrine()
                ->getRepository('AppBundle:Chambre')
                ->find($id);

        $agence = $chambre->getAgence();

        $categories = $this->getDoctrine()
                    ->getRepository('AppBundle:CategorieChambre')
                    ->findBy(array(
                        'agence' => $agence
                    ));

        $tarifs = $this->getDoctrine()
                    ->getRepository('AppBundle:TarifChambre')
                    ->findBy(array(
                        'chambre' => $chambre
                    ));

        return $this->render('HebergementBundle:Chambre:show.html.twig',array(
            'agence' => $agence,
            'chambre' => $chambre,
            'categories' => $categories,
            'tarifs' => $tarifs,
        ));
    }

    public function searchAction(Request $request)
    {
        $nb_pers = $request->request->get('nb_pers');
        $categorie = $request->request->get('categorie');
        $date_entree = $request->request->get('date_entree');
        $date_sortie = $request->request->get('date_sortie');


        $arrivee = \DateTime::createFromFormat('j/m/Y', $date_entree);
        $depart = \DateTime::createFromFormat('j/m/Y', $date_sortie);

        if( $date_entree == $date_sortie )
        {
            $depart->modify('+1 day');
        }

        $nb_nuit = $depart->diff( $arrivee )->days;
        $nb_nuit = $nb_nuit > 0 ? $nb_nuit : 1;

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        $chambres = $this->getDoctrine()
                    ->getRepository('AppBundle:Chambre')
                    ->search(
                        $agence->getId(),
                        $nb_pers,
                        $categorie,
                        $arrivee,
                        $depart
                    );

        $template = $this->renderView('HebergementBundle:Chambre:search.html.twig',array(
            'chambres' => $chambres,
        ));

        return new JsonResponse(array(
            'template' => $template,
            'nb_nuit' => $nb_nuit,
        ));
        
    }

    public function tarifAction(Request $request)
    {
        $id = $request->request->get('id');
        $nb_pers = $request->request->get('nb_pers');

        $chambre = $this->getDoctrine()
                ->getRepository('AppBundle:Chambre')
                ->find($id);

        $tarif = $this->getDoctrine()
                    ->getRepository('AppBundle:TarifChambre')
                    ->findOneBy(array(
                        'chambre' => $chambre,
                        'nbPers' => $nb_pers,
                    ));

        if (empty($tarif)) {
            $tarif = $this->getDoctrine()
                    ->getRepository('AppBundle:TarifChambre')
                    ->findOneBy(array(
                        'chambre' => $chambre,
                        'nbPers' => 1,
                    ));

            if (empty($tarif)) {
                return new JsonResponse(array(
                    'chambre' => $chambre->getNom()
                ));
            } else {
                return new JsonResponse(array(
                    'chambre' => $chambre->getNom(),
                    'montant' => $tarif->getMontant() * $nb_pers,
                    'petit_dejeuner' => $tarif->getPetitDejeuner(),
                    'montant_petit_dejeuner' => $tarif->getMontantPetitDejeuner() * $nb_pers,
                ));
            }

        } else {
            return new JsonResponse(array(
                'chambre' => $chambre->getNom(),
                'montant' => $tarif->getMontant(),
                'petit_dejeuner' => $tarif->getPetitDejeuner(),
                'montant_petit_dejeuner' => $tarif->getMontantPetitDejeuner(),
            ));
        }

    }
}
