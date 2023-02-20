<?php

namespace ComptabiliteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Mouvement;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DepotRetraitController extends Controller
{
    public function addMouvementAction()
    {
    	$user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        $banques = $this->getDoctrine()
                    ->getRepository('AppBundle:Banque')
                    ->findBy(array(
                        'agence' => $agence
                    ));

        return $this->render('ComptabiliteBundle:DepotRetrait:add-mouvement.html.twig',array(
        	'agence' => $agence,
            'banques' => $banques,
        ));
    }

    public function saveMouvementAction(Request $request)
    {
        $id = $request->request->get('id');
        $date = $request->request->get('date');
        $operation = $request->request->get('operation');
        $num_operation = $request->request->get('num_operation');
        $type_operation = $request->request->get('type_operation');
        $compte_bancaire = $request->request->get('compte_bancaire');
        $montant = $request->request->get('montant');
        $op_nom = $request->request->get('op_nom');

        if ($id) {
            $mouvement = $this->getDoctrine()
                    ->getRepository('AppBundle:Mouvement')
                    ->find($id);
        } else {
            $mouvement = new Mouvement();
        }

        $date = \DateTime::createFromFormat('j/m/Y', $date);

        $mouvement->setDate($date);
        $mouvement->setOperation($operation);
        $mouvement->setNumOperation($num_operation);
        $mouvement->setTypeOperation($type_operation);

        $compte = $this->getDoctrine()
                    ->getRepository('AppBundle:CompteBancaire')
                    ->find($compte_bancaire);

        $mouvement->setCompteBancaire($compte);
        $mouvement->setMontant($montant);
        $mouvement->setOpNom($op_nom);

        $em = $this->getDoctrine()->getManager();
        $em->persist($mouvement);
        $em->flush();

        if ($mouvement->getId()) {
            switch ($operation) {
                case 1:
                    # dépôt...
                    $new_solde = $compte->getSolde() + intval($montant);
                    $compte->setSolde($new_solde);
                    break;
                case 2:
                    # retrait...
                    $new_solde = $compte->getSolde() - intval($montant);
                    $compte->setSolde($new_solde);
                    break;
            }

            $em->persist($compte);
            $em->flush();
        }


        return new Response(200);
        
    }

    public function soldeGeneralAction()
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        $banques = $this->getDoctrine()
                    ->getRepository('AppBundle:Banque')
                    ->findBy(array(
                        'agence' => $agence
                    ));
                    
        return $this->render('ComptabiliteBundle:DepotRetrait:solde-general.html.twig',array(
            'banques' => $banques
        ));
    }

    public function listMouvementAction(Request $request)
    {

        $banque = $request->request->get('banque');
        $compte_bancaire = $request->request->get('compte_bancaire');
        $operation = $request->request->get('operation');
        $type_date = $request->request->get('type_date');
        $mois = $request->request->get('mois');
        $annee = $request->request->get('annee');
        $date_specifique = $request->request->get('date_specifique');
        $debut_date = $request->request->get('debut_date');
        $fin_date = $request->request->get('fin_date');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence_id = $userAgence->getAgence()->getId();

        $mouvements = $this->getDoctrine()
                    ->getRepository('AppBundle:Mouvement')
                    ->list(
                        $banque,
                        $compte_bancaire,
                        $operation,
                        $type_date,
                        $mois,
                        $annee,
                        $date_specifique,
                        $debut_date,
                        $fin_date,
                        $agence_id
                    );

        return new  JsonResponse($mouvements);
    }

    public function exportAction(Request $request)
    {

        $user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();

        $datas = json_decode(urldecode($request->request->get('datas')));
        // $type_date = $request->request->get('type_date');
        // $date_specifique = $request->request->get('date_specifique');

        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $backgroundTitle = '808080';
        $phpExcelObject->getProperties()->setCreator("SHISSAB")
            ->setLastModifiedBy("SHISSAB")
            ->setTitle("Export excel solde générale")
            ->setSubject("Export excel solde générale")
            ->setDescription("Export excel solde générale")
            ->setKeywords("solde générale")
            ->setCategory("export excel");
        $sheet = $phpExcelObject->setActiveSheetIndex(0);


        $sheet->setCellValue('A1', $agence->getNom());
        $sheet->setCellValue('A2', 'Solde générale');

        /*Titre*/
        $sheet->setCellValue('A4', 'Date');
        $sheet->setCellValue('B4', 'Opération');
        $sheet->setCellValue('C4', 'N° opération');
        $sheet->setCellValue('D4', 'Type d\'opération');
        $sheet->setCellValue('E4', 'Banque');
        $sheet->setCellValue('F4', 'Compte bancaire');
        $sheet->setCellValue('G4', 'Personne concerné');
        $sheet->setCellValue('H4', 'Montant');

        $sheet->getStyle('A4:H4')
            ->getFill()
            ->setFillType('solid')
            ->getStartColor()->setRGB('c0c0c0');


        foreach(range('A','H') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        

        $index = 5;

        $total = 0;

        foreach ($datas as $data) {
           $sheet->setCellValue('A'.$index,$data->date); 
           $sheet->setCellValue('B'.$index,$data->operation); 
           $sheet->setCellValue('C'.$index,$data->num_operation); 
           $sheet->setCellValue('D'.$index,$data->type_operation); 
           $sheet->setCellValue('E'.$index,$data->banque); 
           $sheet->setCellValue('F'.$index,$data->compte_bancaire); 
           $sheet->setCellValue('G'.$index,$data->op_nom); 
           $sheet->setCellValue('H'.$index,$data->montant); 

           if ($data->operation == 'Retrait') {
               $sheet->getStyle('A' . $index . ':H' . $index)
                ->getFill()
                ->setFillType('solid')
                ->getStartColor()->setRGB('ed55651a');
           } else {
                $sheet->getStyle('A' . $index . ':H' . $index)
                ->getFill()
                ->setFillType('solid')
                ->getStartColor()->setRGB('2b99021a');
           }

           $total += $data->montant;
           $index++;
        }

        $tindex = $index + 1;

        $sheet->setCellValue('A'.$tindex,'Total'); 
        $sheet->setCellValue('H'.$tindex,$total); 

        $sheet->getStyle('H'.$tindex)
            ->getFill()
            ->setFillType('solid')
            ->getStartColor()->setRGB('b8e4bb');


        $phpExcelObject->getActiveSheet()->setTitle('SOLDE GENERALE');
        $phpExcelObject->setActiveSheetIndex(0);

        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);

        $ext = '.xls';

        $name = 'solde-generale';

        $name = str_replace('/','-',$name);

        $name .= $ext;

        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $name
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }

    public function deleteMouvementAction($id)
    {
        $mouvement = $this->getDoctrine()
                    ->getRepository('AppBundle:Mouvement')
                    ->find($id);

        $operation = $mouvement->getOperation();
        $compte = $mouvement->getCompteBancaire();
        $montant = $mouvement->getMontant();

        if ($operation == 1) {
            $new_solde = $compte->getSolde() - $montant;
        } else {
            $new_solde = $compte->getSolde() + $montant;
        }
        
        $compte->setSolde($new_solde);

        $em = $this->getDoctrine()->getManager();
        $em->persist($compte);
        $em->flush();

        $em->remove($mouvement);
        $em->flush();

        return new JsonResponse(200);
        
    }

    public function editorMouvementAction(Request $request)
    {
        $id = $request->request->get('id');
        $mouvement = $this->getDoctrine()->getRepository('AppBundle:Mouvement')
            ->find($id);

        return $this->render('@Comptabilite/DepotRetrait/editor.html.twig',[
            'mouvement' => $mouvement,
        ]);
    }

    public function updateMouvementAction(Request $request)
    {
        $id = $request->request->get('id');
        $num_operation = $request->request->get('num_operation');
        $montant = $request->request->get('montant');
        $op_nom = $request->request->get('op_nom');

        $mouvement = $this->getDoctrine()
                    ->getRepository('AppBundle:Mouvement')
                    ->find($id);

        $old_amount = $mouvement->getMontant();
        $operation = $mouvement->getOperation();
        $compte = $mouvement->getCompteBancaire();

        $mouvement->setNumOperation($num_operation);
        $mouvement->setMontant($montant);
        $mouvement->setOpNom($op_nom);

        $em = $this->getDoctrine()->getManager();
        $em->persist($mouvement);
        $em->flush();

        switch ($operation) {
            case 1:
                # dépôt...
                $new_solde = $compte->getSolde() - $old_amount + intval($montant);
                $compte->setSolde($new_solde);
                break;
            case 2:
                # retrait...
                $new_solde = $compte->getSolde() + $old_amount - intval($montant);
                $compte->setSolde($new_solde);
                break;
        }

        $em->persist($compte);
        $em->flush();


        return new Response(200);
        
    }

}
