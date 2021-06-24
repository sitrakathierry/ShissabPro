<?php

namespace MenuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use AppBundle\Entity\MenuUtilisateur;
use Symfony\Component\HttpFoundation\Response;

class MenuUtilisateurController extends Controller
{
	public function indexAction()
    {

    	$agents = $this->getDoctrine()
                ->getRepository('AppBundle:AgentGap')
                ->findAll();

        return $this->render('MenuBundle:MenuUtilisateur:index.html.twig',array(
        	'agents' => $agents
        ));
    }

    public function userMenuAction(Request $request, $user)
    {
        if ($request->isXmlHttpRequest()) {

            $utilisateur = $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->find($user);

            if ($utilisateur) {
                $menus = $this->getDoctrine()
                    ->getRepository('AppBundle:MenuUtilisateur')
                    ->getMenuUtilisateur($utilisateur);

                $encoder = new JsonEncoder();
                $normalizer = new ObjectNormalizer();
                $normalizer->setCircularReferenceHandler(function ($object) {
                    return $object->getId();
                });
                $serializer = new Serializer(array($normalizer), array($encoder));
                $data = $serializer->serialize($menus, 'json');
                return new Response($data);
            } else {
                throw new NotFoundHttpException("Utilisateur introuvable.");
            }
        } else {
            throw new AccessDeniedHttpException("Accès refusé.");
        }
    }

    public function userAgentMenuEditAction(Request $request, $user)
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
