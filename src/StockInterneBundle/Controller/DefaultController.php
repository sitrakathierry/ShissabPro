<?php

namespace StockInterneBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\StockInterne;
use AppBundle\Entity\EntreeSortieStockInterne;
use AppBundle\Entity\EntreeSortieStockInterneDetails;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('StockInterneBundle:Default:index.html.twig');
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

        $libelles = $this->getDoctrine()
                    ->getRepository('AppBundle:Libelle')
                    ->findBy(array(
                        'agence' => $agence
                    ));

        return $this->render('StockInterneBundle:Default:add.html.twig',array(
            'agence' => $agence,
            'libelles' => $libelles,
        ));
    }

    public function saveAction(Request $request)
    {
        $id = $request->request->get('id');
        $nom = $request->request->get('nom');
        $prix = $request->request->get('prix');
        $qte = $request->request->get('qte');
        $unite = $request->request->get('unite');
        $portion = $request->request->get('portion');
        $description = $request->request->get('description');
        $libelle = $request->request->get('libelle');
        $dateCreation = new \DateTime('now');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        if ($id) {
            $stockInterne = $this->getDoctrine()
                ->getRepository('AppBundle:StockInterne')
                ->find($id);
        } else {
            $stockInterne = new StockInterne();
        }

        $stockInterne->setNom($nom);
        $stockInterne->setPrix($prix);
        $stockInterne->setStock($qte);
        $stockInterne->setUnite($unite);
        $stockInterne->setPortion($portion);
        $stockInterne->setDescription($description);
        $stockInterne->setAgence($agence);

        if ($libelle) {
            $libelle = $this->getDoctrine()
                ->getRepository('AppBundle:Libelle')
                ->find($libelle);

            $stockInterne->setLibelle($libelle);

        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($stockInterne);
        $em->flush();

        if (!$id) {
            $entree = new EntreeSortieStockInterne();

            $entree->setDate($dateCreation);
            $entree->setTotal($prix * $qte);
            $entree->setAgence($agence);
            $entree->setType(1);

            $em->persist($entree);
            $em->flush();

            $entreeDetails = new EntreeSortieStockInterneDetails();

            $entreeDetails->setDate($dateCreation);
            $entreeDetails->setQte($qte);
            $entreeDetails->setPortion($portion);
            $entreeDetails->setPrix($prix);
            $entreeDetails->setTotal($prix * $qte);
            $entreeDetails->setDescription(' Ajout stock interne :  ' . $nom . ' le ' . $dateCreation->format('d/m/Y') . ' ('. $qte . ' ' . $unite .')' );
            $entreeDetails->setStockInterne($stockInterne);
            $entreeDetails->setEntreeSortieStockInterne($entree);

            $em->persist($entreeDetails);
            $em->flush();
        }


        return new JsonResponse(array(
            'id' => $stockInterne->getId()
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

        return $this->render('StockInterneBundle:Default:consultation.html.twig', array(
            'agences' => $agences,
            'userAgence' => $userAgence,
        ));
    }

    public function listAction(Request $request)
    {
        $agence = $request->request->get('agence');
        $recherche_par = $request->request->get('recherche_par');
        $a_rechercher = $request->request->get('a_rechercher');

        $stocks  = $this->getDoctrine()
                        ->getRepository('AppBundle:StockInterne')
                        ->getList($agence,
                            $recherche_par,
                            $a_rechercher
                        );

        return new JsonResponse($stocks);
    }

    public function showAction($id)
    {

        $stockInterne = $this->getDoctrine()
                ->getRepository('AppBundle:StockInterne')
                ->find($id);

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $libelles = $this->getDoctrine()
                    ->getRepository('AppBundle:Libelle')
                    ->findBy(array(
                        'agence' => $agence
                    ));


        return $this->render('StockInterneBundle:Default:show.html.twig',array(
            'agence' => $agence,
            'stockInterne' => $stockInterne,
            'libelles' => $libelles,
        ));
    }
}
