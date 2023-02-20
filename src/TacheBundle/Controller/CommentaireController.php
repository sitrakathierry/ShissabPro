<?php

namespace TacheBundle\Controller;

use AppBundle\Entity\Commentaire;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentaireController extends Controller
{
    public function addAction(Request $request)
    {
        $idtache = $request->request->get('idtache');
        $comment_content = $request->request->get('comment_content');
        
        if(!empty($comment_content))
        {
            $user = $this->getUser();
            $userAgence = $this->getDoctrine()
                        ->getRepository('AppBundle:UserAgence')
                        ->findOneBy(array(
                            'user' => $user
                        ));
            $agence = $userAgence->getAgence();
            // $agenceId = $agence->getId() ;
            $dateCreation = new \DateTime('now', new \DateTimeZone("+3"));
    
            $commentaire = new Commentaire() ;
    
            $commentaire->setIdPcomment($user->getId()) ;
            $commentaire->setContenu($comment_content) ;
            $commentaire->setIdtache($idtache) ;
            $commentaire->setTypeComment("AG") ;
            $commentaire->setDateCreatedAt($dateCreation) ;
    
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();
            return new Response("Succes") ;
        }
        else
        {
            return new Response("Commentaire vide") ;
        }

    }
}
