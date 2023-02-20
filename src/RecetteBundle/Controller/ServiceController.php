<?php

namespace RecetteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ServiceController extends Controller
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

        return $this->render('RecetteBundle:Service:index.html.twig',array(
            'agence' => $agence
        ));
    }

    public function listAction(Request $request)
    {
        $recherche_par = $request->request->get('recherche_par');
        $a_rechercher = $request->request->get('a_rechercher');
        $type_date = $request->request->get('type_date');
        $mois = $request->request->get('mois');
        $annee = $request->request->get('annee');
        $date_specifique = $request->request->get('date_specifique');
        $debut_date = $request->request->get('debut_date');
        $fin_date = $request->request->get('fin_date');
        $par_agence = $request->request->get('par_agence');

        $results1 = $this->getDoctrine()
            ->getRepository('AppBundle:FactureServiceDetails')
            ->recette(
                $recherche_par,
                $a_rechercher,
                $type_date,
                $mois,
                $annee,
                $date_specifique,
                $debut_date,
                $fin_date,
                $par_agence
            );

        $results2 = $this->getDoctrine()
            ->getRepository('AppBundle:FactureProduitServiceDetails')
            ->recetteService(
                $recherche_par,
                $a_rechercher,
                $type_date,
                $mois,
                $annee,
                $date_specifique,
                $debut_date,
                $fin_date,
                $par_agence
            );

        $results = array_merge($results1, $results2);

        return new JsonResponse($results);
    }
}
