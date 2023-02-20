<?php

namespace SitewebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Siteweb;
use AppBundle\Entity\SitewebApropos;
use Api\ConfigBundle\Entity\Config;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SitewebBundle:Default:index.html.twig');
    }

    public function menuAction($id)
    {
        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id);

        $autorisations = $this->getDoctrine()
                ->getRepository('AppBundle:Autorisation')
                ->findBy(array(
                    'siteweb' => $siteweb
                ));

        $formulaires = $this->getDoctrine()
                ->getRepository('AppBundle:Formulaire')
                ->findBy(array(
                    'siteweb' => $siteweb
                ));

        return $this->render('SitewebBundle:Default:menu.html.twig', array(
            'siteweb' => $siteweb,
            'autorisations' => $autorisations,
            'formulaires' => $formulaires,
        ));
    }

    public function addAction()
    {
        return $this->render('SitewebBundle:Default:add.html.twig');
    }

    public function saveAction(Request $request)
    {
        $id = $request->request->get('id');
        $nom = $request->request->get('nom');
        $lien = $request->request->get('lien');
        $description = $request->request->get('description');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        if ($id) {
            $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id);

            $sitewebAPropos = $this->getDoctrine()
                ->getRepository('AppBundle:SitewebApropos')
                ->findOneBy(array(
                    'siteweb' => $siteweb
                ));
        } else {
            $siteweb = new Siteweb();
            $sitewebAPropos = new SitewebApropos();
            $cle = uniqid();
            $siteweb->setCle($cle);
        }

        $siteweb->setNom($nom);
        $siteweb->setLien($lien);
        $siteweb->setDescription($description);
        $siteweb->setAgence($agence);

        $em = $this->getDoctrine()->getManager();
        $em->persist($siteweb);
        $em->flush();

        $sitewebAPropos->setSiteweb($siteweb);
        $em->persist($sitewebAPropos);
        $em->flush();

        $config = $this->getDoctrine()
                ->getRepository('ApiConfigBundle:Config')
                ->findOneBy(array(
                    'siteweb' => $siteweb
                ));

        if (!$config) {
            $config = new Config();

            $apiKey = $this->generateApiKey();
            $config->setApiKey($apiKey);
            $config->setSiteweb($siteweb);

            $em->persist($config);
            $em->flush();
        }


        return new JsonResponse(array(
            'id' => $siteweb->getId()
        ));
        
    }

    public function consultationAction()
    {
        return $this->render('SitewebBundle:Default:consultation.html.twig');
    }

    public function listAction(Request $request)
    {
        $recherche_par = $request->request->get('recherche_par');
        $a_rechercher = $request->request->get('a_rechercher');

        $sites = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->list($recherche_par, $recherche_par);

        return new JsonResponse($sites);

        
    }

    public function showAction($id)
    {
        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id);

        return $this->render('SitewebBundle:Default:show.html.twig', array(
            'siteweb' => $siteweb
        ));
    }

    public function aproposAction($id)
    {
        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id);

        $sitewebAPropos = $this->getDoctrine()
                ->getRepository('AppBundle:SitewebApropos')
                ->findOneBy(array(
                    'siteweb' => $siteweb
                ));

        $sections = $this->getDoctrine()
                ->getRepository('AppBundle:Section')
                ->findBy(array(
                    'siteweb' => $siteweb
                ));

        foreach ($sections as $section) {
            $valeur = $this->getDoctrine()
                ->getRepository('AppBundle:SectionValeur')
                ->findOneBy(array(
                    'section' => $section
                ));

            $section->setValeur($valeur);
        }

        return $this->render('SitewebBundle:Default:apropos.html.twig', array(
            'siteweb' => $siteweb,
            'sitewebAPropos' => $sitewebAPropos,
            'sections' => $sections,
        ));
    }

    public function saveAProposAction(Request $request)
    {
        $id_apropos = $request->request->get('id_apropos');
        $id_siteweb = $request->request->get('id_siteweb');
        $logo_img = $request->request->get('logo_img');
        $slogon = $request->request->get('slogon');
        $apropos = $request->request->get('apropos');
        $titre = $request->request->get('titre');
        $adresse = $request->request->get('adresse');
        $tel_fixe = $request->request->get('tel_fixe');
        $tel_mobile = $request->request->get('tel_mobile');
        $email = $request->request->get('email');
        $facebook = $request->request->get('facebook');

        if ($id_apropos) {
            $sitewebAPropos = $this->getDoctrine()
                ->getRepository('AppBundle:SitewebApropos')
                ->find($id_apropos);
        } else {
            $sitewebAPropos = new SitewebApropos();
        }

        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id_siteweb);

        $sitewebAPropos->setLogo($logo_img);
        $sitewebAPropos->setSlogon($slogon);
        $sitewebAPropos->setApropos($apropos);
        $sitewebAPropos->setSiteweb($siteweb);
        $sitewebAPropos->setTitre($titre);
        $sitewebAPropos->setAdresse($adresse);
        $sitewebAPropos->setTelFixe($tel_fixe);
        $sitewebAPropos->setTelMobile($tel_mobile);
        $sitewebAPropos->setEmail($email);
        $sitewebAPropos->setFacebook($facebook);

        $em = $this->getDoctrine()->getManager();
        $em->persist($sitewebAPropos);
        $em->flush();

        return new JsonResponse(array(
            'id' => $sitewebAPropos->getId()
        ));

    }

    public function generateApiKey($algo = 'haval256,5', $iteration = 300)
    {
        $digest = hash($algo, uniqid() . microtime(), true);
        for ($i=0; $i < 300; $i++) { 
            $digest = hash($algo, $digest, true);
        }
        return base64_encode($digest);
    }
}
