<?php

namespace TacheBundle\Controller;

use AppBundle\Entity\TypeTache;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TypeTacheController extends Controller
{
    public function addAction(Request $request)
    {
        $nomTypeTache = $request->request->get('element');
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();
        $agenceId = $agence->getId() ;

        $typetache = new TypeTache() ;
        // date_default_timezone_set("Africa/Maputo");
        $dateCreation = new \DateTime('now', new \DateTimeZone("+3"));
        // $dateCreation = date('Y-m-d H:i:s') ;
        $typetache->setNomTypeTache($nomTypeTache) ;
        $typetache->setIdAgence($agenceId) ;
        $typetache->setDateCreatedAt($dateCreation) ;
 
        $em = $this->getDoctrine()->getManager();
        $em->persist($typetache);
        $em->flush();

        $baseTypeTache = $this->getDoctrine()
                    ->getRepository('AppBundle:TypeTache')
                    ->getAllTypeTache($agenceId);

        return new JsonResponse($baseTypeTache) ;
    }

}
