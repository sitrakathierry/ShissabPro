<?php

namespace SitewebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Formulaire;
use AppBundle\Entity\FormulaireDetails;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class FormulaireController extends Controller
{
	public function indexAction($id)
    {
        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id);

        $formulaires = $this->getDoctrine()
                ->getRepository('AppBundle:Formulaire')
                ->findBy(array(
                    'siteweb' => $siteweb
                ));

        return $this->render('SitewebBundle:Formulaire:index.html.twig',array(
            'siteweb' => $siteweb,
            'formulaires' => $formulaires,
        ));
    }

    public function saveAction(Request $request)
    {
        $id = $request->request->get('id');
        $id_siteweb = $request->request->get('id_siteweb');
        $nom = $request->request->get('nom');
        $slug = $request->request->get('slug');
        $description = $request->request->get('description');
        $fields = $request->request->get('fields');
        $remove = $request->request->get('remove');
        $update = $request->request->get('update');

        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id_siteweb);

        if ($id) {
            $formulaire = $this->getDoctrine()
                ->getRepository('AppBundle:Formulaire')
                ->find($id);
        } else {
            $formulaire = new Formulaire();
        }

        $formulaire->setNom($nom);
        $formulaire->setSlug($slug);
        $formulaire->setDescription($description);
        $formulaire->setSiteweb($siteweb);

        $em = $this->getDoctrine()->getManager();
        $em->persist($formulaire);
        $em->flush();

        $details = $this->getDoctrine()
                ->getRepository('AppBundle:FormulaireDetails')
                ->findBy(array(
                   'formulaire' => $formulaire
                ));

        // foreach ($details as $detail) {
        //     $em->remove($detail);
        //     $em->flush();
        // }

        if ($remove && !empty($remove)) {
            foreach ($remove as $value) {
                $detail = $this->getDoctrine()
                    ->getRepository('AppBundle:FormulaireDetails')
                    ->find($value['id']);

                if ($detail) {
                    $em->remove($detail);
                    $em->flush();
                }
            }
        }

        if ($update && !empty($update)) {
            foreach ($update as $field) {
                if ($field['name'] != "") {

                    $detail = $this->getDoctrine()
                        ->getRepository('AppBundle:FormulaireDetails')
                        ->find($field['id']);

                    $detail->setNom($field['name']);
                    $detail->setSlug($field['slug']);
                    $detail->setFormulaire($formulaire);

                    $em->persist($detail);
                    $em->flush();
                }
            }
        }

        if ($fields && !empty($fields)) {
            foreach ($fields as $field) {
                if ($field['name'] != "") {

                    $detail = new FormulaireDetails();

                    $detail->setNom($field['name']);
                    $detail->setSlug($field['slug']);
                    $detail->setFormulaire($formulaire);

                    $em->persist($detail);
                    $em->flush();
                }
            }
        }

        
        return new JsonResponse(array(
            'id' => $formulaire->getId()
        ));
    }

    public function showAction($id)
    {
        $formulaire = $this->getDoctrine()
                ->getRepository('AppBundle:Formulaire')
                ->find($id);

        $siteweb = $formulaire->getSiteweb();

        $details = $this->getDoctrine()
                ->getRepository('AppBundle:FormulaireDetails')
                ->findBy(array(
                    'formulaire' => $formulaire
                ));

        return $this->render('SitewebBundle:Formulaire:show.html.twig',array(
            'siteweb' => $siteweb,
            'formulaire' => $formulaire,
            'details' => $details,
        ));
    }

    public function consultationAction($id)
    {
        $formulaire = $this->getDoctrine()
                ->getRepository('AppBundle:Formulaire')
                ->find($id);

        $siteweb = $formulaire->getSiteweb();

        $clients = $this->getDoctrine()
                ->getRepository('AppBundle:FormulaireClient')
                ->findBy(array(
                    'formulaire' => $formulaire
                ));

        return $this->render('SitewebBundle:Formulaire:consultation.html.twig',array(
            'siteweb' => $siteweb,
            'formulaire' => $formulaire,
            'clients' => $clients,
        ));
    }

    public function displayAction($id)
    {
        $formulaireClient = $this->getDoctrine()
                ->getRepository('AppBundle:FormulaireClient')
                ->find($id);

        $formulaire = $formulaireClient->getFormulaire();
        $siteweb = $formulaire->getSiteweb();

        $formulaireClientDetails = $this->getDoctrine()
                ->getRepository('AppBundle:FormulaireClientDetails')
                ->findBy(array(
                    'formulaireClient' => $formulaireClient
                ));

        return $this->render('SitewebBundle:Formulaire:display.html.twig',array(
            'siteweb' => $siteweb,
            'formulaire' => $formulaire,
            'formulaireClient' => $formulaireClient,
            'formulaireClientDetails' => $formulaireClientDetails,
        ));
    }

}
