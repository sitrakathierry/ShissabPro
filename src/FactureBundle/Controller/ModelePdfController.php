<?php

namespace FactureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ModelePdfController extends Controller
{
	public function editorAction(Request $request)
	{
		
	    $f_id = $request->request->get('f_id');
	    $objet = $request->request->get('objet');

	    $facture = $this->getDoctrine()
                ->getRepository('AppBundle:Facture')
                ->find($f_id);

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

	    return $this->render('FactureBundle:ModelePdf:editor.html.twig',array(
            'pdfs' => $pdfs,
            'facture' => $facture,
        ));


	}

	public function saveAction(Request $request)
	{

	    $f_id = $request->request->get('f_id');
	    $f_modele_pdf = $request->request->get('f_modele_pdf');

		$facture = $this->getDoctrine()
                ->getRepository('AppBundle:Facture')
                ->find($f_id);

        $modelePdf = $this->getDoctrine()
                ->getRepository('AppBundle:ModelePdf')
                ->find($f_modele_pdf);

        $facture->setModelePdf($modelePdf);

        $em = $this->getDoctrine()->getManager();
        $em->persist($facture);
        $em->flush();

        return new JsonResponse(array(
        	'id' => $facture->getId()
        ));

	}
}
