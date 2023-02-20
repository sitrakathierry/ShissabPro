<?php

namespace HebergementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\ConditionHebergement;


class ConditionController extends Controller
{
	public function indexAction()
    {

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        $condition = $this->getDoctrine()
                    ->getRepository('AppBundle:ConditionHebergement')
                    ->findOneBy(array(
                        'agence' => $agence
                    ));

        return $this->render('HebergementBundle:Condition:index.html.twig',array(
            'condition' => $condition,
            'agence' => $agence,
        ));
    }

    public function saveAction(Request $request)
    {
        $agence_id = $request->request->get('agence_id');
        $text = $request->request->get('text');

        $agence = $this->getDoctrine()
                    ->getRepository('AppBundle:Agence')
                    ->find($agence_id);

        $condition = $this->getDoctrine()
                    ->getRepository('AppBundle:ConditionHebergement')
                    ->findOneBy(array(
                        'agence' => $agence
                    ));
                    
        if (!$condition) {
            $condition = new ConditionHebergement();
        }


        $condition->setText($text);
        $condition->setAgence($agence);

        $em = $this->getDoctrine()->getManager();
        $em->persist($condition);
        $em->flush();

        return new Response(200);
        
    }
}
