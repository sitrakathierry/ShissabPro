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
        $listeSocietes = $this->getDoctrine()
                                ->getRepository('AppBundle:Agence')
                                ->findBy(
                                    array(),
                                    array('nom' => 'ASC')
                                );
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
            /*if(count($arrayListe) > 0){
            	var_dump($arrayListe);die;
            }*/
            
            $listeUsers[$v->getId()]['listes'] = $arrayListe;
            $listeUsers[$v->getId()]['nb'] = count($listeUsers[$v->getId()]['listes']);
        }

        return $this->render('PermissionBundle:Default:acces-menu.html.twig', array(
            'listeSocietes' => $listeSocietes,
            'listeUsers' => $listeUsers,
        ));
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
                     $menus_id = $request->request->get('menus');
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
                                $societe_menu = new MenuParAgence();
                                $societe_menu
                                    ->setAgence($agence)
                                    ->setMenu($menu);
                                $em->persist($societe_menu);
                            }
                        }
                        $em->flush();
                    }
                    $menus = $this->getDoctrine()
                                      ->getRepository('AppBundle:Menu')
                                      ->getMenuParAgence($agence);

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
