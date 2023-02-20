<?php

namespace MenuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\AccessRole;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class MenuController extends Controller
{
    public function indexAction()
    {
        return $this->render('MenuBundle:Default:acces.html.twig');
    }

    public function accessRoleAction($role)
    {
    	$result  = $this->getDoctrine()
                        ->getRepository('AppBundle:Menu')
                        ->byRole('', null, true);

        $menus = [];

        foreach ($result as $menu) {
        	$item = array(
        		'id' => $menu->getId(),
        		'label' => $menu->getName(),
        		'child' => array(),
        		'icon' => $menu->getIcon(),
        		'has' => $this->hasMenu($menu,$role)
        	);

        	if (count($menu->getChild()) > 0) {

        		foreach ($menu->getChild() as $menu_item) {
        			$child_item = array(
        				'id' => $menu_item->getId(),
        				'label' => $menu_item->getName(),
        				'child' => array(),
                        'has' => $this->hasMenu($menu_item,$role)
        			);

        			if (count($menu_item->getChild()) > 0) {
        				
        				foreach ($menu_item->getChild() as $value) {
        					$child_value = array(
		        				'id' => $value->getId(),
		        				'label' => $value->getName(),
		        				'child' => array(),
                                'has' => $this->hasMenu($value,$role)
		        			);

		        			array_push($child_item['child'], $child_value);
        				}
        			}

        			array_push($item['child'], $child_item);

        		}
        	}

        	array_push($menus, $item);
        }



        return $this->render('MenuBundle:Default:menu-role.html.twig', array(
            'menus' => $menus
        ));
    }

    public function hasMenu($menu,$role)
    {
        $user = $this->getUser();
        $has  = $this->getDoctrine()
                        ->getRepository('AppBundle:Menu')
                        ->roleHasMenu($menu,$role,$user);

        return $has;
    }

    public function saveAction(Request $request)
    {
        $access_role = $request->request->get('access_role');
        $checked_menus = json_decode($request->request->get('checked_menus'),true);
        $unchecked_menus = json_decode($request->request->get('unchecked_menus'),true);

        foreach ($checked_menus as $checked) {

            $menu  = $this->getDoctrine()
                        ->getRepository('AppBundle:Menu')
                        ->find($checked);

            $acces  = $this->getDoctrine()
                        ->getRepository('AppBundle:AccessRole')
                        ->findOneBy(array(
                            'role' => $access_role,
                            'menu' => $menu
                        ));

            if (!$acces) {
                $new_access = new AccessRole();

                $new_access->setRole($access_role);
                $new_access->setMenu($menu);

                $em = $this->getDoctrine()->getManager();
                $em->persist($new_access);
                $em->flush();

            }
            

        }

        foreach ($unchecked_menus as $unchecked) {
            $menu  = $this->getDoctrine()
                        ->getRepository('AppBundle:Menu')
                        ->find($unchecked);

            $acces  = $this->getDoctrine()
                        ->getRepository('AppBundle:AccessRole')
                        ->findOneBy(array(
                            'role' => $access_role,
                            'menu' => $menu
                        ));

            if ($acces) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($acces);
                $em->flush();
            }
        }

        return new JsonResponse(200);

    }

    public function listeMenuAction($admin = 0)
    {
        $menus_id = [];

        $menus_complet = $this->getDoctrine()
            ->getRepository('AppBundle:Menu')
            ->getAllMenu($admin);

        if ($this->isGranted('ROLE_ADMIN')) {
            $menus = $this->getDoctrine()
                ->getRepository('AppBundle:Menu')
                ->findAll();
            /** @var Menu $menu */
            foreach ($menus as $menu) {
                $menus_id[] = $menu->getId();
            }
        } else{  
            $userAgence  = $this->getDoctrine()
                                ->getRepository('AppBundle:UserAgence')
                                ->findOneBy(array(
                                    'user' => $this->getUser()
                                ));

            $agence = $userAgence->getAgence();

            $menus = $this->getDoctrine()
                          ->getRepository('AppBundle:Menu')
                          ->getMenuParAgence($agence);

            if(count($menus) == 0){   
                $menus = $menus_complet;    
            }

            foreach ( $menus as $menu ) {
                $menus_id[] = $menu->getMenu()->getId();
            }
        }

        return $this->render('@Menu/Default/menu-liste.html.twig', array(
            'menus_complet' => $menus_complet,
            'menus_id' => $menus_id,
        ));
    }

}
