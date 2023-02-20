<?php

namespace MotifDechargeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use AppBundle\Entity\MotifDecharge;

class MotifController extends Controller
{
	public function indexAction()
    {
        return $this->render('MotifDechargeBundle:Motif:index.html.twig');
    }

    public function saveAction(Request $request)
    {
    	$id = $request->request->get('id');
    	$libelle = $request->request->get('libelle');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

    	if ($id) {
	    	$motif = $this->getDoctrine()
	            ->getRepository('AppBundle:MotifDecharge')
	            ->find($id);
    	} else {
    		$motif = new MotifDecharge();
    	}

    	$motif->setLibelle($libelle);
        $motif->setAgence($agence);

    	$em = $this->getDoctrine()->getManager();
        $em->persist($motif);
        $em->flush();
    	
        return new Response(200);

    }

    public function listAction(Request $request)
    {

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

    	$motifs = $this->getDoctrine()
	            ->getRepository('AppBundle:MotifDecharge')
	            ->list($agence->getId());


	    return new JsonResponse( $motifs );
    }

    public function deleteAction($id)
    {
        $motif = $this->getDoctrine()
        	->getRepository('AppBundle:MotifDecharge')
            ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($motif);
        $em->flush();

        return new JsonResponse(200);
        
    }

    public function editorAction(Request $request)
    {
        $id = $request->request->get('id');
        $motif = $this->getDoctrine()->getRepository('AppBundle:MotifDecharge')
            ->find($id);

        return $this->render('@MotifDecharge/Motif/editor.html.twig',[
            'motif' => $motif,
        ]);
    }

    public function updateAgenceAction()
    {
        $decharges = $this->getDoctrine()
            ->getRepository('AppBundle:Decharge')
            ->findAll();

        $em = $this->getDoctrine()->getManager();
        
        foreach ($decharges as $decharge) {
            $motif_decharge = $decharge->getMotifDecharge();
            $agence_decharge = $decharge->getAgence();
            $agence_motif = $motif_decharge->getAgence();

            if (!$agence_motif) {
                $motif_decharge->setAgence($agence_decharge);
                $em->persist($motif_decharge);
                $em->flush();
            } else {
                if ($agence_decharge != $agence_motif) {
                    $motif_agence = new MotifDecharge();

                    $motif_agence->setLibelle( $motif_decharge->getLibelle() );
                    $motif_agence->setAgence( $agence_decharge );

                    $em->persist($motif_agence);
                    $em->flush();

                    $decharge->setMotifDecharge($motif_agence);
                    $em->persist($motif_agence);
                    $em->flush();
                }
            }


        }

        echo "succes"; die();
    }
}
