<?php

namespace ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Fournisseur;

class FournisseurController extends Controller
{

	public function indexAction()
    {
        return $this->render('ProduitBundle:Fournisseur:index.html.twig');
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

        $fournisseurs = $this->getDoctrine()
                ->getRepository('AppBundle:Fournisseur')
                ->list($agence_id);

        return new JsonResponse($fournisseurs);
    }

    public function saveAction(Request $request)
    {
        $nom = $request->request->get('nom');
        $nom_contact = $request->request->get('nom_contact');
        $adresse = $request->request->get('adresse');
        $tel_bureau = $request->request->get('tel_bureau');
        $tel_mobile = $request->request->get('tel_mobile');
        $email = $request->request->get('email');
        $id = $request->request->get('id');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        if ($id) {
            $fournisseur = $this->getDoctrine()
                    ->getRepository('AppBundle:Fournisseur')
                    ->find($id);
        } else {
            $fournisseur = new Fournisseur();
        }

        $fournisseur->setNom($nom);
        $fournisseur->setNomContact($nom_contact);
        $fournisseur->setAdresse($adresse);
        $fournisseur->setTelBureau($tel_bureau);
        $fournisseur->setTelMobile($tel_bureau);
        $fournisseur->setEmail($email);
        $fournisseur->setAgence($agence);

        $em = $this->getDoctrine()->getManager();
        $em->persist($fournisseur);
        $em->flush();

        if ($fournisseur->getId()) {
            return new Response(200);
        }
        
    }

    public function deleteAction($id)
    {
        $fournisseur  = $this->getDoctrine()
                        ->getRepository('AppBundle:Fournisseur')
                        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($fournisseur);
        $em->flush();

        return new JsonResponse(200);
        
    }

    public function editorAction(Request $request)
    {
        $id = $request->request->get('id');
        $fournisseur = $this->getDoctrine()
        	->getRepository('AppBundle:Fournisseur')
            ->find($id);

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        return $this->render('@Produit/Fournisseur/editor.html.twig',[
            'fournisseur' => $fournisseur,
        ]);
    }

}
