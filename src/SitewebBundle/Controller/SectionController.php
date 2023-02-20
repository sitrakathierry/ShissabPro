<?php

namespace SitewebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Section;
use AppBundle\Entity\SectionValeur;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class SectionController extends Controller
{
	public function indexAction($id)
    {
        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id);

        $sections = $this->getDoctrine()
                ->getRepository('AppBundle:Section')
                ->findBy(array(
                    'siteweb' => $siteweb
                ));

        return $this->render('SitewebBundle:Section:index.html.twig',array(
            'siteweb' => $siteweb,
            'sections' => $sections,
        ));
    }

    public function saveAction(Request $request)
    {
        $id = $request->request->get('id');
        $id_siteweb = $request->request->get('id_siteweb');
        $nom = $request->request->get('nom');
        $type = $request->request->get('type');
        $slug = $request->request->get('slug');

        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id_siteweb);

        if ($id) {
            $section = $this->getDoctrine()
                ->getRepository('AppBundle:Section')
                ->find($id);
        } else {
            $section = new Section();
        }

        $section->setSiteweb($siteweb);
        $section->setNom($nom);
        $section->setType($type);
        $section->setSlug($slug);

        $em = $this->getDoctrine()->getManager();
        $em->persist($section);
        $em->flush();

        return new JsonResponse(array(
            'id' => $section->getId()
        ));
    }

    public function saveValeurAction(Request $request)
    {
        $id_siteweb = $request->request->get('id_siteweb');
        $sections = $request->request->get('sections');

        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id_siteweb);

        foreach ($sections as $slug => $value) {
            $section = $this->getDoctrine()
                ->getRepository('AppBundle:Section')
                ->findOneBy(array(
                    'siteweb' => $siteweb,
                    'slug' => $slug,
                ));


            $sectionValeur = $this->getDoctrine()
                ->getRepository('AppBundle:SectionValeur')
                ->findOneBy(array(
                    'section' => $section
                ));

            if (!$sectionValeur) {
                $sectionValeur = new SectionValeur();
            }

            $sectionValeur->setValeur($value);
            $sectionValeur->setSection($section);

            $em = $this->getDoctrine()->getManager();
            $em->persist($sectionValeur);
            $em->flush();

        }

        return new JsonResponse(array(
            'success' => true
        ));
    }
}
