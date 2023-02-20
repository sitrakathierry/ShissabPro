<?php

namespace HebergementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\CategorieChambre;
use AppBundle\Entity\TarifCategorieChambre;

class CategorieController extends Controller
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

       	$types = $this->getDoctrine()
                    ->getRepository('AppBundle:TypeChambre')
                    ->findBy(array(
                        'agence' => $agence
                    ));

        return $this->render('HebergementBundle:Categorie:index.html.twig',array(
        	'agence' => $agence,
            'types' => $types,
        ));
    }

    public function showAction($id)
    {

        $categorie = $this->getDoctrine()
                    ->getRepository('AppBundle:CategorieChambre')
                    ->find($id);

        $tarifs = $this->getDoctrine()
                    ->getRepository('AppBundle:TarifCategorieChambre')
                    ->findBy(array(
                        'categorieChambre' => $categorie
                    ));

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        $types = $this->getDoctrine()
                    ->getRepository('AppBundle:TypeChambre')
                    ->findBy(array(
                        'agence' => $agence
                    ));

        return $this->render('HebergementBundle:Categorie:show.html.twig',array(
            'agence' => $agence,
            'types' => $types,
            'categorie' => $categorie,
            'tarifs' => $tarifs,
        ));
    }

    public function listAction(Request $request)
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence_id = $userAgence->getAgence()->getId();

        $categories = $this->getDoctrine()
                ->getRepository('AppBundle:CategorieChambre')
                ->list($agence_id);

        return new JsonResponse($categories);
    }

    public function saveAction(Request $request)
    {
        $type = $request->request->get('type');
        $nom = $request->request->get('nom');
        $description = $request->request->get('description');
        $caracteristiques = $request->request->get('caracteristiques');
        $id = $request->request->get('id');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        if ($id) {
            $categorie = $this->getDoctrine()
                    ->getRepository('AppBundle:CategorieChambre')
                    ->find($id);
        } else {
            $categorie = new CategorieChambre();
        }

        $type = $this->getDoctrine()
                    ->getRepository('AppBundle:TypeChambre')
                    ->find($type);

        $categorie->setNom($nom);
        $categorie->setDescription($description);
        $categorie->setTypeChambre($type);
        $categorie->setCaracteristiques($caracteristiques);
        $categorie->setAgence($agence);

        $em = $this->getDoctrine()->getManager();
        $em->persist($categorie);
        $em->flush();

        $details = $this->getDoctrine()
                    ->getRepository('AppBundle:TarifCategorieChambre')
                    ->findBy(array(
                        'categorieChambre' => $categorie
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
                    $tarif = new TarifCategorieChambre();

                    $tarif->setNbPers($nb_pers);
                    $tarif->setMontant($montant);
                    $tarif->setPetitDejeuner($petit_dejeuner);
                    $tarif->setMontantPetitDejeuner($montant_petit_dejeuner);
                    $tarif->setCategorieChambre($categorie);

                    $em->persist($tarif);
                    $em->flush();
                }
            }
        }


        if ($categorie->getId()) {
            return new Response(200);
        }
        
    }

    public function deleteAction($id)
    {
        $categorie  = $this->getDoctrine()
                        ->getRepository('AppBundle:CategorieChambre')
                        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();

        return new JsonResponse(200);
        
    }

    public function editorAction(Request $request)
    {
        $id = $request->request->get('id');
        $categorie = $this->getDoctrine()
        	->getRepository('AppBundle:CategorieChambre')
            ->find($id);

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

       	$types = $this->getDoctrine()
                    ->getRepository('AppBundle:TypeChambre')
                    ->findBy(array(
                        'agence' => $agence
                    ));

        return $this->render('@Hebergement/Categorie/editor.html.twig',[
            'categorie' => $categorie,
            'types' => $types,
        ]);
    }

    public function tarifsAction($id)
    {
        $categorie = $this->getDoctrine()
                    ->getRepository('AppBundle:CategorieChambre')
                    ->find($id);

        $tarifs = $this->getDoctrine()
                    ->getRepository('AppBundle:TarifCategorieChambre')
                    ->findBy(array(
                        'categorieChambre' => $categorie
                    ));

        return $this->render('@Hebergement/Categorie/tarifs.html.twig',[
            'categorie' => $categorie,
            'tarifs' => $tarifs,
        ]);
    }
}
