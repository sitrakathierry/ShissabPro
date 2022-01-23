<?php

namespace CommercialBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Evenement;

class EvenementController extends Controller
{
	public function editorAction(Request $request)
    {
        // $id = $request->request->get('id');
        // $banque = $this->getDoctrine()->getRepository('AppBundle:Banque')
        //     ->find($id);

        return $this->render('@Commercial/Evenement/editor.html.twig',[
            // 'banque' => $banque,
        ]);
    }

    public function saveAction(Request $request)
    {
        $id = $request->request->get('id');
        $titre = $request->request->get('title');
        $description = $request->request->get('description');
        $type = $request->request->get('type');
        $date = $request->request->get('start');
        $datestr = $request->request->get('datestr');
        $couleur = $request->request->get('backgroundColor');

        var_dump($datestr);die();

        if ($id) {
            $evenement = $this->getDoctrine()
                    ->getRepository('AppBundle:Evenement')
                    ->find($id);

            if (!$evenement) {
                $evenement = new Evenement();
            }

        } else {
            $evenement = new Evenement();
        }

        $evenement->setId($id);
        $evenement->setTitre($titre);
        $evenement->setDescription($description);
        $evenement->setType($type);
        $evenement->setDate( \DateTime::createFromFormat('j/m/Y', $datestr) );
        $evenement->setCouleur($couleur);

        $em = $this->getDoctrine()->getManager();
        $em->persist($evenement);
        $em->flush();

        if ($evenement->getId()) {
            return new Response(200);
        }
        
    }
}
