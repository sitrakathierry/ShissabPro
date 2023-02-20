<?php

namespace TacheBundle\Controller;

use AppBundle\Entity\Assignation;
use AppBundle\Entity\HistoTypeTache;
use AppBundle\Entity\Tache;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TacheBundle:Default:index.html.twig');
    }
    public function addAction()
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $agenceId = $agence->getId() ;

        $accessTache = $this->getDoctrine()
                        ->getRepository('AppBundle:Menu')
                        ->getParamUser($user->getId()); 

        $employeAgence = $this->getDoctrine()
                            ->getRepository('AppBundle:UserAgence')
                            ->findBy(array(
                                'agence' => $agence
                            )); 
        
        $basePers = $employeAgence ;
        
        $baseTypeTache = $this->getDoctrine()
                    ->getRepository('AppBundle:TypeTache')
                    ->getAllTypeTache($agenceId);

        return $this->render('TacheBundle:Default:add.html.twig', array(
            "basePers" => $basePers,
            "baseTypeTache" => $baseTypeTache,
            "accessTache" => $accessTache,
            "user" => $user->getId()
        ));
    }
    public function consultationAction()
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence() ;
        $agenceId = $agence->getId() ; 
        
        $accessTache = $this->getDoctrine()
                        ->getRepository('AppBundle:Menu')
                        ->getParamUser($user->getId()); 
        
        
        
        if(!empty($accessTache))
        {
            $baseTache = $this->getDoctrine()
                        ->getRepository('AppBundle:Tache')
                        ->getAllTache($agenceId);  
        }
        else
        {
            $baseTache = $this->getDoctrine()
                    ->getRepository('AppBundle:Tache')
                    ->getAllTacheUser($agenceId,$user->getId());  
        }

        $tabTypeTache = array() ;
        $tabTachePers = array() ;
        $tabCommentTache = array() ;

        foreach ($baseTache as $uneTache) {
            $baseTacheType = $this->getDoctrine()
                    ->getRepository('AppBundle:Tache')
                    ->getAllTacheTypeTache($uneTache["id"]);
                    
            array_push($tabTypeTache,$baseTacheType) ;

            $baseTachePers = $this->getDoctrine()
                    ->getRepository('AppBundle:Tache')
                    ->getAllTachePersonne($uneTache["id"]);
            array_push($tabTachePers,$baseTachePers) ;

            $baseCommnetaire = $this->getDoctrine()
                    ->getRepository('AppBundle:Tache')
                    ->getAllCommentTache($uneTache["id"]);
            
            array_push($tabCommentTache, $baseCommnetaire) ;

            if ($uneTache["statut"] != 2) {
                $this->getDoctrine()
                ->getRepository('AppBundle:Tache')
                    ->verificationTache($uneTache["id"], $uneTache["date_debut"], $uneTache["date_fin"]);
            }
        }

        $employeAgence = $this->getDoctrine()
            ->getRepository('AppBundle:UserAgence')
            ->findBy(array(
                'agence' => $agence
            ));

        $basePers = $employeAgence;

        $baseTypeTache = $this->getDoctrine()
            ->getRepository('AppBundle:TypeTache')
            ->getAllTypeTache($agenceId);
        
        return $this->render('TacheBundle:Default:consultation.html.twig',array(
            'tabTachePers' => $tabTachePers,
            'baseTache' => $baseTache,
            'tabTypeTache' => $tabTypeTache,
            'tabCommentTache' => $tabCommentTache,
            "basePers" => $basePers,
            "baseTypeTache" => $baseTypeTache,
            "accessTache" => $accessTache,
            "user" => $user->getId()
        ));
    }

    public function saveAction(Request $request)
    {
        // récupérer les informations postés
        $tache = $request->request->get('t_nom');
        $t_pers_assigne = $request->request->get('t_pers_assigne'); // tableau de personne
        $t_date_debut = $request->request->get('t_date_debut');
        $t_date_fin = $request->request->get('t_date_fin');
        $t_duree = $request->request->get('t_duree');
        $t_type_duree = $request->request->get('t_type_duree');
        $t_type_tache = $request->request->get('t_type_tache'); // tableau de type de tâche
        $t_description = $request->request->get('t_description');

        $dateCreation = new \DateTime('now', new \DateTimeZone("+3"));

        // récupérer l'id de l'agence
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();
        $agenceId = $agence->getId() ;
        
        $enregistre = true ;

        if(empty($tache) || empty($t_date_debut) || empty($t_date_debut) || empty($t_date_fin) || empty($t_type_duree) || empty($t_duree) || empty($t_pers_assigne) || empty($t_type_tache))
        {
            $enregistre = false ;
        }

        if($enregistre)
        {
            $timestampDebut = strtotime($t_date_debut); 
            $timestampFin = strtotime($t_date_fin); 
            if ($timestampFin < $timestampDebut)
                $enregistre = false ;

            if($enregistre)
            {
                if((is_numeric($t_duree) &&  $t_duree > 0))
                {
                    $tacheEntity = new Tache() ;
        
                    $dateDebut =  new \DateTime($t_date_debut, new \DateTimeZone("+3"));
                    $dateFin =  new \DateTime($t_date_fin, new \DateTimeZone("+3"));
            
                    $tacheEntity->setTache($tache) ;
                    $tacheEntity->setIdAgence($agenceId) ; 
                    $tacheEntity->setDateDebut($dateDebut) ;
                    $tacheEntity->setDateFin($dateFin) ;
                    $tacheEntity->setDuree(intval($t_duree)) ; 
                    $tacheEntity->setTypeDuree($t_type_duree) ;
                    $tacheEntity->setDescription($t_description) ;
        
                    // Comparer la date debut de tache avec la date en cours
                    $date1 = $t_date_debut; 
                    $date2 = date("Y-m-d"); 
                    $timestamp1 = strtotime($date1); 
                    $timestamp2 = strtotime($date2); 
                    if ($timestamp1 <= $timestamp2)
                        $tacheEntity->setStatut(1) ; // Non commencé
                    else
                        $tacheEntity->setStatut(0) ; // En Cours
        
                    $tacheEntity->setDateCreatedAt($dateCreation) ;
                    $tacheEntity->setIsDelete(NULL) ;
        
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($tacheEntity);
                    $em->flush();
        
                    $idNew = $tacheEntity->getId() ;
                    for ($i=0; $i < count($t_type_tache); $i++) { 
                        $histoTypeTache = new HistoTypeTache() ;
                        $histoTypeTache->setIdtache($idNew) ;
                        $histoTypeTache->setIdtypetache($t_type_tache[$i]) ;
                        $histoTypeTache->setDateCreatedAt($dateCreation) ;
                        $em->persist($histoTypeTache);
                        $em->flush();
                    }
        
                    for ($j=0; $j < count($t_pers_assigne); $j++) { 
                        $assignation = new Assignation() ;
                        $assignation->setIdtache($idNew) ;
                        $assignation->setIdpersonne($t_pers_assigne[$j]) ;
                        $assignation->setDateCreatedAt($dateCreation) ;
                        $em->persist($assignation);
                        $em->flush();
                    }
        
        
                    return new Response("Succes") ;
                }
                else
                {
                    return new Response("Vérifier la durée") ;
                }
            }
            else
            {
                return new Response("La date Fin doit être supérieure à la date début");
            }
             
        }
        else
        {
            return new Response("Remplissez bien les champs") ;
        }
         
    }

    public function rechercherAction(Request $request)
    {
        $pers_assigne = $request->request->get('pers_assigne');
        $type_tache = $request->request->get('type_tache');
        $date_debut = $request->request->get('date_debut');
        $date_fin = $request->request->get('date_fin');
        $duree = $request->request->get('duree');
        $type_duree = $request->request->get('type_duree');
        $statutTache = $request->request->get('statutTache');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
            ->getRepository('AppBundle:UserAgence')
            ->findOneBy(array(
                'user' => $user
            ));
        $agence = $userAgence->getAgence();
        $agenceId = $agence->getId();

        $accessTache = $this->getDoctrine()
            ->getRepository('AppBundle:Menu')
            ->getParamUser($user->getId());


        if (empty($accessTache)) {
            $pers_assigne = $user->getId();
            $baseTache = $this->getDoctrine()
                ->getRepository('AppBundle:Tache')
                ->rechercheTache(
                    $agenceId,
                    $statutTache,
                    $date_debut,
                    $date_fin,
                    $duree,
                    $type_duree,
                    $pers_assigne,
                    $type_tache
                );
        } else {

            $baseTache = $this->getDoctrine()
                ->getRepository('AppBundle:Tache')
                ->rechercheTache(
                    $agenceId,
                    $statutTache,
                    $date_debut,
                    $date_fin,
                    $duree,
                    $type_duree,
                    $pers_assigne,
                    $type_tache
                );
        }


        $tabTypeTache = array();
        $tabTachePers = array();
        $tabCommentTache = array();

        foreach ($baseTache as $uneTache) {
            $baseTacheType = $this->getDoctrine()
                ->getRepository('AppBundle:Tache')
                ->getAllTacheTypeTache($uneTache["id"]);

            array_push($tabTypeTache, $baseTacheType);

            $baseTachePers = $this->getDoctrine()
                ->getRepository('AppBundle:Tache')
                ->getAllTachePersonne($uneTache["id"]);
            array_push($tabTachePers, $baseTachePers);

            $baseCommnetaire = $this->getDoctrine()
                ->getRepository('AppBundle:Tache')
                ->getAllCommentTache($uneTache["id"]);

            array_push($tabCommentTache, $baseCommnetaire);

            if ($uneTache["statut"] != 2) {
                $this->getDoctrine()
                    ->getRepository('AppBundle:Tache')
                    ->verificationTache($uneTache["id"], $uneTache["date_debut"], $uneTache["date_fin"]);
            }
        }

        $template = $this->renderView('TacheBundle:Default:consult_rech.html.twig', array(
            'tabTachePers' => $tabTachePers,
            'baseTache' => $baseTache,
            'tabTypeTache' => $tabTypeTache,
            'tabCommentTache' => $tabCommentTache
        ));

        return new Response($template);
    }

    public function detailAction($idTache)
    {
        $user = $this->getUser();
        $uneTache = $this->getDoctrine()
                    ->getRepository('AppBundle:Tache')
                    ->getTacheById($idTache);  
        
        $baseTacheType = $this->getDoctrine()
                    ->getRepository('AppBundle:Tache')
                    ->getAllTacheTypeTache($uneTache["id"]);
                    
            // array_push($tabTypeTache,$baseTacheType) ;

        $baseTachePers = $this->getDoctrine()
                    ->getRepository('AppBundle:Tache')
                    ->getAllTachePersonne($uneTache["id"]);
            // array_push($tabTachePers,$baseTachePers) ;

        $baseCommnetaire = $this->getDoctrine()
                    ->getRepository('AppBundle:Tache')
                    ->getAllCommentTache($uneTache["id"]);

        // array_push($tabCommentTache, $baseCommnetaire) ;
        if ($uneTache["statut"] != 2) {
            $this->getDoctrine()
                ->getRepository('AppBundle:Tache')
            ->verificationTache($uneTache["id"], $uneTache["date_debut"], $uneTache["date_fin"]);
        }
        
        $accessTache = $this->getDoctrine()
                ->getRepository('AppBundle:Menu')
                ->getParamUser($user->getId());   

        return $this->render('TacheBundle:Default:detail.html.twig',array(
            'uneTache' => $uneTache,
            'baseTacheType' => $baseTacheType,
            'baseTachePers' => $baseTachePers,
            'baseCommnetaire' => $baseCommnetaire,
            'user' => $user->getId(),
            'accessTache' => $accessTache
        ));
    }

    public function termineAction(Request $request)
    {
        $idTache = $request->request->get('idTache');

        $this->getDoctrine()
            ->getRepository('AppBundle:Tache')
            ->findeTache($idTache);

        return new Response("Succes");
    }

    public function supprimeAction(Request $request)
    {
        $idTache = $request->request->get('idTache');

        $this->getDoctrine()
            ->getRepository('AppBundle:Tache')
            ->supprimeTache($idTache);

        return new Response("Succes");
    }

    public function modifieAction(Request $request)
    {
        $idTache = $request->request->get('idTache');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
            ->getRepository('AppBundle:UserAgence')
            ->findOneBy(array(
                'user' => $user
            ));
        $agence = $userAgence->getAgence();
        $agenceId = $agence->getId();

        $employeAgence = $this->getDoctrine()
            ->getRepository('AppBundle:UserAgence')
            ->getAllUserAgence($agenceId);

        $AllTypeTache = $this->getDoctrine()
            ->getRepository('AppBundle:TypeTache')
            ->getAllTypeTache($agenceId);

        $uneTache = $this->getDoctrine()
            ->getRepository('AppBundle:Tache')
            ->getTacheById($idTache);

        $baseTacheType = $this->getDoctrine()
            ->getRepository('AppBundle:Tache')
            ->getAllTacheTypeTache($idTache);

        // array_push($tabTypeTache,$baseTacheType) ;

        $baseTachePers = $this->getDoctrine()
            ->getRepository('AppBundle:Tache')
            ->getAllTachePersonne($idTache);

        // array_push($tabCommentTache, $baseCommnetaire) ;

        $accessTache = $this->getDoctrine()
            ->getRepository('AppBundle:Menu')
            ->getParamUser($user->getId());

        $data = array(
            $uneTache,
            $employeAgence,
            $baseTachePers,
            $AllTypeTache,
            $baseTacheType,
            $accessTache
        );

        return new JsonResponse($data);
    }

    public function validModifieAction(Request $request)
    {
        $mdf_tache = $request->request->get('mdf_tache');
        $mdf_date_debut = $request->request->get('mdf_date_debut');
        $mdf_date_fin = $request->request->get('mdf_date_fin');
        $mdf_duree = $request->request->get('mdf_duree');
        $mdf_type_duree = $request->request->get('mdf_type_duree');
        $mdf_personne = $request->request->get('mdf_personne');
        $mdf_type_tache = $request->request->get('mdf_type_tache');
        $mdf_description = $request->request->get('mdf_description');
        $idTache = $request->request->get('idTache');

        $enregistre = true;

        $timestampDebut = strtotime($mdf_date_debut);
        $timestampFin = strtotime($mdf_date_fin);
        if ($timestampFin < $timestampDebut)
            $enregistre = false;

        if ($enregistre) {

            $params = array(
                $mdf_tache,
                $mdf_date_debut,
                $mdf_date_fin,
                $mdf_duree,
                $mdf_type_duree,
                $mdf_description,
                $idTache
            );

            $this->getDoctrine()
                ->getRepository('AppBundle:Tache')
                ->modifieUnetache($params);

            $em = $this->getDoctrine()->getManager();
            $dateCreation = new \DateTime('now', new \DateTimeZone("+3"));
            $i = 0;
            while ($i < count($mdf_personne)) {
                $idPersonne = $mdf_personne[$i];
                $assignPers = $this->getDoctrine()
                    ->getRepository('AppBundle:Assignation')
                    ->verifieAssignPers($idPersonne, $idTache);
                if (empty($assignPers)) {
                    $assignation = new Assignation();
                    $assignation->setIdtache($idTache);
                    $assignation->setIdpersonne($idPersonne);
                    $assignation->setDateCreatedAt($dateCreation);
                    $em->persist($assignation);
                    $em->flush();
                }
                $i++;
            }

            $i = 0;
            while ($i < count($mdf_type_tache)) {
                $idTypetache = $mdf_type_tache[$i];
                $typeTacheItem = $this->getDoctrine()
                    ->getRepository('AppBundle:HistoTypeTache')
                    ->verifieHistoTypeTache($idTypetache, $idTache);

                if (empty($typeTacheItem)) {
                    $histoTypeTache = new HistoTypeTache();
                    $histoTypeTache->setIdtache($idTache);
                    $histoTypeTache->setIdtypetache($idTypetache);
                    $histoTypeTache->setDateCreatedAt($dateCreation);
                    $em->persist($histoTypeTache);
                    $em->flush();
                }
                $i++;
            }


            $baseTacheType = $this->getDoctrine()
                ->getRepository('AppBundle:Tache')
                ->getAllTacheTypeTache($idTache);

            $baseTachePers = $this->getDoctrine()
                ->getRepository('AppBundle:Tache')
                ->getAllTachePersonne($idTache);

            for ($i = 0; $i < count($baseTacheType); $i++) {
                $passe = false;
                for ($j = 0; $j < count($mdf_type_tache); $j++) {
                    if ($baseTacheType[$i]["idtypetache"] == $mdf_type_tache[$j]) {
                        $passe = true;
                        break;
                    }
                }
                if (!$passe) {
                    $this->getDoctrine()
                        ->getRepository('AppBundle:HistoTypeTache')
                        ->affaceHistoTypeTahce($baseTacheType[$i]["idtypetache"], $idTache);
                }
            }

            for ($i = 0; $i < count($baseTachePers); $i++) {
                $passe = false;
                for ($j = 0; $j < count($mdf_personne); $j++) {
                    if ($baseTachePers[$i]["id"] == $mdf_personne[$j]) {
                        $passe = true;
                        break;
                    }
                }
                if (!$passe) {
                    $this->getDoctrine()
                        ->getRepository('AppBundle:Assignation')
                        ->affaceAssignation($baseTachePers[$i]["id"], $idTache);
                }
            }

            return new Response("Succes");
        } else {
            return new Response("La date Fin doit être supérieure à la date début");
        }
    }
}