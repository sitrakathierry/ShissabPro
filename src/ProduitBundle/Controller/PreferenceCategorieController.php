<?php

namespace ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\PreferenceCategorie;
use Symfony\Component\HttpFoundation\JsonResponse;

class PreferenceCategorieController extends Controller
{
	public function indexAction()
    {
        $categories = $this->getDoctrine()
                ->getRepository('AppBundle:CategorieProduit')
                ->findAll();

        $user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));

        $agence = $userAgence->getAgence();

        $preference = $this->getDoctrine()
                ->getRepository('AppBundle:PreferenceCategorie')
                ->findOneBy(array(
                    'agence' => $agence
                ));

        return $this->render('ProduitBundle:PreferenceCategorie:index.html.twig',array(
            'categories' => $categories,
            'preference' => $preference,
        ));
    }

    public function saveAction(Request $request)
    {
        $categories = $request->request->get('categories');

        $user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        $preference = $this->getDoctrine()
                ->getRepository('AppBundle:PreferenceCategorie')
                ->findOneBy(array(
                    'agence' => $agence
                ));

        if (!$preference) {
            $preference = new PreferenceCategorie();
        }

        $preference->setCategories($categories);
        $preference->setAgence($agence);

        $em = $this->getDoctrine()->getManager();
        $em->persist($preference);
        $em->flush();

        return new JsonResponse (array(
            'id' => $preference->getId()
        ));
    }
}
