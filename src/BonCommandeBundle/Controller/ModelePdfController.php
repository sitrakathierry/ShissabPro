<?php

namespace BonCommandeBundle\Controller;

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

	    $bonCommande = $this->getDoctrine()
                ->getRepository('AppBundle:BonCommande')
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

	    return $this->render('BonCommandeBundle:ModelePdf:editor.html.twig',array(
            'pdfs' => $pdfs,
            'bonCommande' => $bonCommande,
        ));


	}

	public function saveAction(Request $request)
	{

	    $id = $request->request->get('id');
	    $f_modele_pdf = $request->request->get('f_modele_pdf');

		$bonCommande = $this->getDoctrine()
                ->getRepository('AppBundle:BonCommande')
                ->find($id);

        $modelePdf = $this->getDoctrine()
                ->getRepository('AppBundle:ModelePdf')
                ->find($f_modele_pdf);

        $bonCommande->setModelePdf($modelePdf);

        $em = $this->getDoctrine()->getManager();
        $em->persist($bonCommande);
        $em->flush();

        return new JsonResponse(array(
        	'id' => $bonCommande->getId()
        ));

	}
}
