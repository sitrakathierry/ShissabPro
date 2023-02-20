<?php

namespace BonLivraisonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ModelePdfController extends Controller
{

	public function editorAction(Request $request)
	{
		
	    $id = $request->request->get('id');
	    $objet = $request->request->get('objet');

	    $bonLivraison = $this->getDoctrine()
                ->getRepository('AppBundle:BonLivraison')
                ->find($id);

	    $user = $this->getUser();
	    $userAgence = $this->getDoctrine()
	                ->getRepository('AppBundle:UserAgence')
	                ->findOneBy(array(
	                    'user' => $user
	                ));
	    $agence = $userAgence->getAgence();

	    $pdfs = $this->getDoctrine()
	                    ->getRepository('AppBundle:PdfAgence')
	                    ->findBy(array(
	                        'agence' => $agence,
	                        'objet' => intval($objet),
	                    ));

	    return $this->render('BonLivraisonBundle:ModelePdf:editor.html.twig',array(
            'pdfs' => $pdfs,
            'bonLivraison' => $bonLivraison,
        ));


	}

	public function saveAction(Request $request)
	{

	    $id = $request->request->get('id');
	    $f_modele_pdf = $request->request->get('f_modele_pdf');

		$bonLivraison = $this->getDoctrine()
                ->getRepository('AppBundle:BonLivraison')
                ->find($id);

        $modelePdf = $this->getDoctrine()
                ->getRepository('AppBundle:ModelePdf')
                ->find($f_modele_pdf);

        $bonLivraison->setModelePdf($modelePdf);

        $em = $this->getDoctrine()->getManager();
        $em->persist($bonLivraison);
        $em->flush();

        return new JsonResponse(array(
        	'id' => $bonLivraison->getId()
        ));

	}

}
