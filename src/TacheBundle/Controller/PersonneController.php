<?php

namespace TacheBundle\Controller;

use AppBundle\Entity\Personne;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PersonneController extends Controller
{
    public function addAction(Request $request) 
    {
        $nomPers = $request->request->get('element');
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();
        $agenceId = $agence->getId() ;

        $personne = new Personne() ;
        // date_default_timezone_set("Africa/Maputo");
        $dateCreation = new \DateTime('now', new \DateTimeZone("+3"));
        // $dateCreation = date('Y-m-d H:i:s') ;
        $personne->setNomPersonne($nomPers) ;
        $personne->setIdAgence($agenceId) ;
        $personne->setDateCreatedAt($dateCreation) ;
 
        $em = $this->getDoctrine()->getManager();
        $em->persist($personne);
        $em->flush();

        $basePers = $this->getDoctrine()
                    ->getRepository('AppBundle:Personne')
                    ->getAllPersonne($agenceId);

        return new JsonResponse($basePers) ;
    }

}
