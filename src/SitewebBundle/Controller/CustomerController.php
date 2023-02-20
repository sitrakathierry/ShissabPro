<?php

namespace SitewebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Customer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class CustomerController extends Controller
{

	public function indexAction($id)
    {
        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id);

        return $this->render('SitewebBundle:Customer:index.html.twig',array(
            'siteweb' => $siteweb
        ));
    }

    public function saveAction(Request $request)
    {
        $id = $request->request->get('id');
        $id_siteweb = $request->request->get('id_siteweb');
        $nom = $request->request->get('nom');
        $customer_img = $request->request->get('customer_img');

        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id_siteweb);

        if ($id) {
            $customer = $this->getDoctrine()
                ->getRepository('AppBundle:Customer')
                ->find($id);
        } else {
            $customer = new Customer();
        }

        $customer->setNom($nom);
        $customer->setImg($customer_img);
        $customer->setSiteweb($siteweb);

        $em = $this->getDoctrine()->getManager();
        $em->persist($customer);
        $em->flush();

        return new JsonResponse(array(
            'id' => $customer->getId()
        ));
        
    }

    public function listAction(Request $request)
    {
        $id_siteweb = $request->request->get('id_siteweb');

        $siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->find($id_siteweb);

        $cle = $siteweb->getCle();

        $customers = $this->getDoctrine()
                ->getRepository('AppBundle:Customer')
                ->list($cle);

        return new JsonResponse($customers);

        
    }

    public function showAction($id)
    {
        $customer = $this->getDoctrine()
                ->getRepository('AppBundle:Customer')
                ->find($id);

        $siteweb = $customer->getSiteweb();

        return $this->render('SitewebBundle:Customer:show.html.twig',array(
            'customer' => $customer,
            'siteweb' => $siteweb,
        ));
    }

    public function deleteAction($id)
    {
        $customer  = $this->getDoctrine()
                        ->getRepository('AppBundle:Customer')
                        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($customer);
        $em->flush();

        return new JsonResponse(200);
    }

    public function disableAction($id)
    {
        $customer = $this->getDoctrine()
                ->getRepository('AppBundle:Customer')
                ->find($id);

        $customer->setDesactive(1);

        $em = $this->getDoctrine()->getManager();
        $em->persist($customer);
        $em->flush();

        return new JsonResponse(array(
            'id' => $customer->getId()
        ));
        
    }

    public function enableAction($id)
    {
        $customer = $this->getDoctrine()
                ->getRepository('AppBundle:Customer')
                ->find($id);

        $customer->setDesactive('');

        $em = $this->getDoctrine()->getManager();
        $em->persist($customer);
        $em->flush();

        return new JsonResponse(array(
            'id' => $customer->getId()
        ));
        
    }

}
