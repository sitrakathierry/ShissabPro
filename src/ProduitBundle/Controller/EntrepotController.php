<?php

namespace ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Entrepot;
use AppBundle\Entity\ProduitEntrepot;
use AppBundle\Entity\Produit;


class EntrepotController extends Controller
{
	public function indexAction()
    {
        return $this->render('ProduitBundle:Entrepot:index.html.twig');
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

        $entrepots = $this->getDoctrine()
                ->getRepository('AppBundle:Entrepot')
                ->list($agence_id);

        return new JsonResponse($entrepots);
    }

    public function saveAction(Request $request)
    {
        $nom = $request->request->get('nom');
        $adresse = $request->request->get('adresse');
        $tel = $request->request->get('tel');
        $id = $request->request->get('id');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        if ($id) {
            $entrepot = $this->getDoctrine()
                    ->getRepository('AppBundle:Entrepot')
                    ->find($id);
        } else {
            $entrepot = new Entrepot();
        }

        $entrepot->setNom($nom);
        $entrepot->setAdresse($adresse);
        $entrepot->setTel($tel);
        $entrepot->setAgence($agence);

        $em = $this->getDoctrine()->getManager();
        $em->persist($entrepot);
        $em->flush();

        if ($entrepot->getId()) {
            return new Response(200);
        }
        
    }

    public function deleteAction($id)
    {
        $entrepot  = $this->getDoctrine()
                        ->getRepository('AppBundle:Entrepot')
                        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($entrepot);
        $em->flush();

        return new JsonResponse(200);
        
    }

    public function editorAction(Request $request)
    {
        $id = $request->request->get('id');
        $entrepot = $this->getDoctrine()
        	->getRepository('AppBundle:Entrepot')
            ->find($id);

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        return $this->render('@Produit/Entrepot/editor.html.twig',[
            'entrepot' => $entrepot,
        ]);
    }

    public function afficheAction(Request $request)
    {
        $entrepot = $request->request->get('entrepot');
        $typeid = $request->request->get('typeid');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();
        $agenceId = $agence->getId() ;

        $listeProduit = array() ;

        if($typeid == 1)
        {
            $listeProduit = $this->getDoctrine()
                            ->getRepository('AppBundle:Produit')
                            ->getAllProduit($agenceId);
            
        }
        else if($typeid == 2)
        {
            $listeProduit = $this->getDoctrine()
                                        ->getRepository('AppBundle:ProduitEntrepot')
                                        ->getProduitParEntrepot($entrepot);
        }
        
        return new JsonResponse($listeProduit) ;
    }
}
