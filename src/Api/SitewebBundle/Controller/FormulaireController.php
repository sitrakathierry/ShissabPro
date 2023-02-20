<?php

namespace Api\SitewebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\FormulaireClient;
use AppBundle\Entity\FormulaireClientDetails;
use Exception;

class FormulaireController extends Controller
{
	public function saveAction(Request $request)
	{
		$sitekey = $request->request->get('sitekey');
		$slug = $request->request->get('slug');

		$nom = $request->request->get('nom');
		$email = $request->request->get('email');
		$objet = $request->request->get('objet');
		$message = $request->request->get('message');

		$siteweb = $this->getDoctrine()
                ->getRepository('AppBundle:Siteweb')
                ->findOneBy(array(
                	'cle' => $sitekey
                ));

        if (!$siteweb) {
            return new JsonResponse(array(
                'status' => 204,
                'message' => 'Invalid sitekey',
            ));
        }

        $formulaire = $this->getDoctrine()
                ->getRepository('AppBundle:Formulaire')
                ->findOneBy(array(
                	'siteweb' => $siteweb,
                	'slug' => $slug,
                ));

        if (!$formulaire) {
            return new JsonResponse(array(
                'status' => 204,
                'message' => 'Invalid slu',
            ));
        }

        $formulaireDetails = $this->getDoctrine()
                ->getRepository('AppBundle:FormulaireDetails')
                ->findBy(array(
                	'formulaire' => $formulaire
                ));

        $formulaireClient = new FormulaireClient();

        $formulaireClient->setEmail($email);
        $formulaireClient->setNom($nom);
        $formulaireClient->setObjet($objet);
        $formulaireClient->setMessage($message);
        $formulaireClient->setDate( new \DateTime('now') );
        $formulaireClient->setStatut(0);
        $formulaireClient->setFormulaire($formulaire);

        try{
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($formulaireClient);
            $em->flush();

            foreach ($formulaireDetails as $field) {
            	$valeur = $request->request->get( $field->getSlug() );

            	if ($valeur) {
            		$formulaireClientDetails = new FormulaireClientDetails();

            		$formulaireClientDetails->setValeur($valeur);
            		$formulaireClientDetails->setFormulaireDetails($field);
            		$formulaireClientDetails->setFormulaireClient($formulaireClient);

                    try {
                		$em->persist($formulaireClientDetails);
                		$em->flush();
                    } catch (Exception $e) {
                        return new JsonResponse(array(
                            'status' => Response::HTTP_BAD_REQUEST,
                            'message' => $e->getMessage()
                        ));
                    }

            	}
            }

            return new JsonResponse(array(
            	'id' => $formulaireClient->getId(),
                'status' => Response::HTTP_CREATED,
                'message' => 'Saving successful',
            ));

        } catch (Exception $e) {
            return new JsonResponse(array(
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage()
            ));
        }

	}
}
