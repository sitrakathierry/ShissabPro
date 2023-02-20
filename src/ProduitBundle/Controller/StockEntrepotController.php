<?php

namespace ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class StockEntrepotController extends Controller
{
	public function indexAction()
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

        $userEntrepot = $this->getDoctrine()
                    ->getRepository('AppBundle:UserEntrepot')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $categories = [];

        $agence = $userAgence->getAgence();

        $produits = $this->getDoctrine()
                ->getRepository('AppBundle:Produit')
                ->findBy(array(
                    'agence' => $agence
                ));

        $preference = $this->getDoctrine()
                ->getRepository('AppBundle:PreferenceCategorie')
                ->findOneBy(array(
                    'agence' => $agence
                ));

        if ($preference) {
            $categoriesList = $preference->getCategoriesList();

            foreach ($categoriesList as $id) {
                $item = $this->getDoctrine()
                    ->getRepository('AppBundle:CategorieProduit')
                    ->find($id);
                array_push($categories, $item);
            }
        }

        $entrepots = $this->getDoctrine()
                ->getRepository('AppBundle:Entrepot')
                ->findBy(array(
                    'agence' => $agence
                ));

		return $this->render('ProduitBundle:StockEntrepot:index.html.twig', array(
			'agences' => $agences,
            'userAgence' => $userAgence,
            'categories' => $categories,
            'userEntrepot' => $userEntrepot,
            'entrepots' => $entrepots,
            'produits' => $produits,
	    ));
	}

    public function listAction(Request $request)
    {
        $agence = $request->request->get('agence');
        $entrepot = $request->request->get('entrepot');
        $recherche_par = $request->request->get('recherche_par');
        $a_rechercher = $request->request->get('a_rechercher');
        $categorie = $request->request->get('categorie');
        $produit_id = $request->request->get('produit_id');

        $produits  = $this->getDoctrine()
                        ->getRepository('AppBundle:ProduitEntrepot')
                        ->getList(
                            $agence,
                            $entrepot,
                            $recherche_par,
                            $a_rechercher,
                            $categorie,
                            $produit_id
                        );

        return new JsonResponse($produits);
    }

    public function showAction($id)
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $userEntrepot = $this->getDoctrine()
                    ->getRepository('AppBundle:UserEntrepot')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $agence = $userAgence->getAgence();

        $produitEntrepot = $this->getDoctrine()
                ->getRepository('AppBundle:ProduitEntrepot')
                ->find($id);

        $produit = $produitEntrepot->getProduit();

        $categories = $this->getDoctrine()
                    ->getRepository('AppBundle:CategorieProduit')
                    ->findAll();

        $print = false;

        $pdfs = $this->getDoctrine()
                    ->getRepository('AppBundle:PdfAgence')
                    ->findBy(array(
                        'agence' => $agence,
                        'objet' => 1,
                    ));
        if (!empty($pdfs)) {
            $print = true;
        }

        $entrepots = $this->getDoctrine()
                ->getRepository('AppBundle:Entrepot')
                ->findBy(array(
                    'agence' => $agence
                ));
                
        return $this->render('ProduitBundle:StockEntrepot:show.html.twig',array(
            'userEntrepot' => $userEntrepot,
            'produitEntrepot' => $produitEntrepot,
            'produit' => $produit,
            'entrepots' => $entrepots,
            'agence' => $agence,
            'categories' => $categories,
            'print' => $print,
        ));
    }

    public function deleteAction($id)
    {
        $produitEntrepot  = $this->getDoctrine()
                        ->getRepository('AppBundle:ProduitEntrepot')
                        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($produitEntrepot);
        $em->flush();

        return new JsonResponse(200);
    }

    public function editAction(Request $request)
    {
        $id = $request->request->get('id');
        $indice = $request->request->get('indice');

        $produitEntrepot  = $this->getDoctrine()
                        ->getRepository('AppBundle:ProduitEntrepot')
                        ->find($id);

        $produitEntrepot->setIndice($indice);

        $em = $this->getDoctrine()->getManager();
        $em->persist($produitEntrepot);
        $em->flush();

        return new JsonResponse(200);
        
    }

}
