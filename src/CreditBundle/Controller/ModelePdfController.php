<?php

namespace CreditBundle\Controller;

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

	    $credit = $this->getDoctrine()
                ->getRepository('AppBundle:Credit')
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

	    return $this->render('CreditBundle:ModelePdf:editor.html.twig',array(
            'pdfs' => $pdfs,
            'credit' => $credit,
        ));


	}

	public function saveAction(Request $request)
	{

    	$id = $request->request->get('id');
    	$f_modele_pdf = $request->request->get('f_modele_pdf');

		$credit = $this->getDoctrine()
	                ->getRepository('AppBundle:Credit')
	                ->find($id);

        $modelePdf = $this->getDoctrine()
                ->getRepository('AppBundle:ModelePdf')
                ->find($f_modele_pdf);

        $credit->setModelePdf($modelePdf);

        $em = $this->getDoctrine()->getManager();
        $em->persist($credit);
        $em->flush();

        return new JsonResponse(array(
        	'id' => $credit->getId()
        ));

	}

}
