<?php

namespace PermissionBundle\Controller;

use AppBundle\Entity\Agence;
use AppBundle\Entity\MenuParAgence;
use AppBundle\Entity\MenuUtilisateur;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PermissionBundle:Default:index.html.twig');
    }

    public function getAccesAction()
    {
    	$listeUsers = [];
        $user = $this->getUser();
        $role = '';
        if ($user) {
            $role = $user->getRoles()[0];
        }

        if($role == 'ROLE_AGENT')
            throw new AccessDeniedHttpException("Accès refusé.");

        if($role == "ROLE_SUPER_ADMIN" || $role == "ROLE_ADMIN"){
        	$listeSocietes = $this->getDoctrine()
                                ->getRepository('AppBundle:Agence')
                                ->findBy(
                                    array(),
                                    array('nom' => 'ASC')
                                );
        }else{
        	$userAgence  = $this->getDoctrine()
			                    ->getRepository('AppBundle:UserAgence')
			                    ->findOneBy(array(
			                        'user' => $user
			                    ));

        	$agence = $userAgence->getAgence();
        	$listeSocietes = [];
        	$listeSocietes[] = $agence;
        }

        foreach ($listeSocietes as $k => $v) {
            $arrayListe = [];
            $arrayListeId = [];
            $data = $this->getDoctrine()->getRepository('AppBundle:UserAgence')->countUserByAgence($v);
            foreach ($data as $key => $value) {
                if(!in_array($value->getId(), $arrayListeId)){
                    $arrayListeId[] = $value->getId();
                    $arrayListe[] = $value;
                }
            }
            
            $listeUsers[$v->getId()]['listes'] = $arrayListe;
            $listeUsers[$v->getId()]['nb'] = count($listeUsers[$v->getId()]['listes']);
        }

        if($role == "ROLE_SUPER_ADMIN" || $role == "ROLE_ADMIN"){
        	return $this->render('PermissionBundle:Default:acces-menu.html.twig', array(
	            'listeSocietes' => $listeSocietes,
	            'listeUsers' => $listeUsers
	        ));
        }else{
        	return $this->render('PermissionBundle:Default:acces-menu-user.html.twig', array(
	            'listeUsers' => $listeUsers
	        ));
        }
    }

    public function operateurMenuAction(Request $request, User $user)
    {
        if ($request->isXmlHttpRequest()) {
            if ($user) {
                $menus = $this->getDoctrine()
                              ->getRepository('AppBundle:Menu')
                              ->getMenuUser($user);

                $userAgence  = $this->getDoctrine()
                                    ->getRepository('AppBundle:UserAgence')
                                    ->findOneBy(array(
                                        'user' => $user
                                    ));

                $agence = $userAgence->getAgence();
                
                $menusAgence = $this->getDoctrine()
                                    ->getRepository('AppBundle:Menu')
                                    ->getMenuParAgence($agence);

                $encoder = new JsonEncoder();
                $normalizer = new ObjectNormalizer();
                $normalizer->setCircularReferenceHandler(function ($object) {
                    return $object->getId();
                });
                $serializer = new Serializer(array($normalizer), array($encoder));
                $data = [
                            'menus' => $menus,
                            'menusRefuser' => $menusAgence
                        ];
                return new JsonResponse($serializer->serialize($data, 'json'));
            } else {
                throw new NotFoundHttpException("Utilisateur introuvable.");
            }
        } else {
            throw new AccessDeniedHttpException("Accès refusé.");
        }
    }

    public function accesOperateurMenuAction(Request $request, Agence $agence)
    {
        if ($request->isXmlHttpRequest()) {
            $menus = $this->getDoctrine()
                          ->getRepository('AppBundle:Menu')
                          ->getMenuParAgence($agence);

            $encoder = new JsonEncoder();
            $normalizer = new ObjectNormalizer();
            $normalizer->setCircularReferenceHandler(function ($object) {
                return $object->getId();
            });
            $serializer = new Serializer(array($normalizer), array($encoder));
            return new Response($serializer->serialize($menus, 'json'));
        } else {
            throw new AccessDeniedHttpException("Accès refusé.");
        }
    } 

    public function societeAccesMenuEditAction(Request $request, Agence $agence){
        if ($request->isXmlHttpRequest()) {
            if ($request->isMethod('POST')) {
                try {
                     $menus_id =  $request->request->get('menus');
                     $menuUsersId = [];
                     $this->getDoctrine()
                          ->getRepository('AppBundle:Menu')
                          ->removeAgenceMenus($agence);
                     if ($menus_id && is_array($menus_id)) {
                        $em = $this->getDoctrine()
                                   ->getManager();
                        foreach ($menus_id as $menu_id) {
                            $menu = $this->getDoctrine()
                                         ->getRepository('AppBundle:Menu')
                                         ->find($menu_id['menu']);
                            if ($menu) {
                                $menuUsersId[] = $menu_id['menu'];
                                $societe_menu = new MenuParAgence();
                                $societe_menu
                                    ->setAgence($agence)
                                    ->setMenu($menu);
                                $em->persist($societe_menu);
                            }
                        }
                        $em->flush();
                    }

                    $userAgences = $this->getDoctrine()
                                        ->getRepository('AppBundle:UserAgence')
                                        ->findBy(
                                            array('agence' => $agence)
                                        );
                    foreach ($userAgences as $key => $userAgence) {
                        $menuUsers =  $this->getDoctrine()
                                           ->getRepository('AppBundle:MenuUtilisateur')
                                           ->findBy(
                                                array('user' => $userAgence->getUser())
                                            );
                        if(count($menuUsers) > 0){
                            foreach ($menuUsers as $key => $menuUser) {
                                if(!in_array($menuUser->getMenu()->getId(), $menuUsersId)){
                                    $em->remove($menuUser);
                                }
                            }
                            $em->flush();
                        }
                    }
                    $menus = $this->getDoctrine()
                                      ->getRepository('AppBundle:Menu')
                                      ->getMenuParAgence($agence);

                    $this->updateMenuResponsable($menus, $agence);

                    $encoder = new JsonEncoder();
                    $normalizer = new ObjectNormalizer();
                    $normalizer->setCircularReferenceHandler(function ($object) {
                        return $object->getId();
                    });
                    $serializer = new Serializer(array($normalizer), array($encoder));

                    $data = [
                        'erreur' => false,
                        'menus' => $menus,
                    ];
                    return new JsonResponse($serializer->serialize($data, 'json'));
                } catch (\Exception $ex) {
                    $data = [
                        'erreur' => true,
                        'erreur_text' => "Une erreur est survenue.",
                    ];
                    return new JsonResponse(json_encode($data));
                }
            } else {
                throw new AccessDeniedHttpException('Accès refusé.');
            }
        } else {
            throw new AccessDeniedHttpException('Accès refusé.');
        }
    }

    public function updateMenuResponsable($menus, $agence)
    {

        $userAgences = $this->getDoctrine()
                                        ->getRepository('AppBundle:UserAgence')
                                        ->findBy(
                                            array('agence' => $agence)
                                        );

        foreach ($userAgences as $userAgence) {
            $user = $userAgence->getUser();

            $role = $user->getRoles()[0];

            if ($role == "ROLE_RESPONSABLE") {


                foreach ($menus as $item) {



                    $exist =  $this->getDoctrine()
                       ->getRepository('AppBundle:MenuUtilisateur')
                       ->findOneBy(array(
                            'menu' => $item->getMenu(),
                            'user' => $user,
                       ));

                    if (!$exist) {

                        if ($item->getMenu()->getName() == 'Facture') {
                            // var_dump("not exist");die();
                        }
                        
                        $menuUtilisateur = new MenuUtilisateur();

                        $menuUtilisateur->setMenu($item->getMenu());
                        $menuUtilisateur->setUser($user);

                        $em = $this->getDoctrine()
                                       ->getManager();
                        $em->persist($menuUtilisateur);

                        $em->flush();

                    } else {
                        if ($item->getMenu()->getName() == 'Facture') {
                            // var_dump("exist");die();
                        }
                    }
                }
            }


        }
    }

    public function utilisateurMenuEditAction(Request $request, $user)
    {
        if ($request->isXmlHttpRequest()) {
            if ($request->isMethod('POST')) {
                try {
                    $user = $this->getDoctrine()
                                ->getRepository('AppBundle:User')
                                ->find($user);
                    if ($user) {
                        $menus_id = $request->request->get('menus');
                        $this->getDoctrine()
                             ->getRepository('AppBundle:MenuUtilisateur')
                             ->removeMenuUtilisateur($user);
                        if ($menus_id && is_array($menus_id)) {
                            $em = $this->getDoctrine()
                                       ->getManager();
                            foreach ($menus_id as $menu_id) {
                                $menu = $this->getDoctrine()
                                             ->getRepository('AppBundle:Menu')
                                             ->find($menu_id['menu']);
                                if ($menu) {
                                    $user_menu = new MenuUtilisateur();
                                    $user_menu
                                        ->setUser($user)
                                        ->setMenu($menu);
                                    $em->persist($user_menu);
                                }
                            }
                            $em->flush();
                        }
                        $menus = $this->getDoctrine()
                                      ->getRepository('AppBundle:MenuUtilisateur')
                                      ->getMenuUtilisateur($user);

                        $encoder = new JsonEncoder();
                        $normalizer = new ObjectNormalizer();
                        $normalizer->setCircularReferenceHandler(function ($object) {
                            return $object->getId();
                        });
                        $serializer = new Serializer(array($normalizer), array($encoder));

                        $data = [
                            'erreur' => false,
                            'menus' => $menus,
                        ];
                        return new JsonResponse($serializer->serialize($data, 'json'));
                    } else {
                        throw new NotFoundHttpException("Utilisateur introuvable.");
                    }
                } catch (\Exception $ex) {
                    $data = [
                        'erreur' => true,
                        'erreur_text' => "Une erreur est survenue.",
                    ];
                    return new JsonResponse(json_encode($data));
                }
            } else {
                throw new AccessDeniedHttpException('Accès refusé.');
            }
        } else {
            throw new AccessDeniedHttpException('Accès refusé.');
        }
    }
} 
