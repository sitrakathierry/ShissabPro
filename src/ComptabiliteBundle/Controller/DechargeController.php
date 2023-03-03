<?php

namespace ComptabiliteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Decharge;
use AppBundle\Entity\DesignationDepense;
use AppBundle\Entity\DetailsDepense;
use AppBundle\Entity\EcheanceAchatDepense;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DechargeController extends Controller
{

    public function indexAction()
    {
        return $this->render('ComptabiliteBundle:Decharge:index.html.twig');
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

        $motifs = $this->getDoctrine()
                    ->getRepository('AppBundle:MotifDecharge')
                    ->findBy(array(
                        'agence' => $agence
                    ));

        $designations = $this->getDoctrine()
            ->getRepository('AppBundle:DesignationDepense')
            ->findAll();

        return $this->render('ComptabiliteBundle:Decharge:add.html.twig',array(
            'agence' => $agence,
            'motifs' => $motifs,
            "designations" => $designations
        ));
    }

    public function saveAction(Request $request)
    {
        $id = $request->request->get('id');
        $beneficiaire = $request->request->get('beneficiaire');
        $cheque = $request->request->get('cheque');
        $montant = $request->request->get('montant');
        $raison = $request->request->get('raison');
        $date = $request->request->get('date');
        $lettre = $request->request->get('lettre');
        $mode_paiement = $request->request->get('mode_paiement');
        $devise = $request->request->get('devise');
        $service = $request->request->get('service');
        $motif = $request->request->get('motif');
        $date_cheque = $request->request->get('date_cheque');
        $date_validation = $request->request->get('date_validation');
        $mois_facture = $request->request->get('mois_facture');
        $statut = $request->request->get('statut');
        $virement = $request->request->get('virement');
        $date_virement = $request->request->get('date_virement');
        $carte_bancaire = $request->request->get('carte_bancaire');
        $num_facture = $request->request->get('num_facture');
        $montant_echeance_paye = $request->request->get('montant_echeance_paye');
        $type_payment = $request->request->get('type_payment');
        $fournisseur = $request->request->get('fournisseur'); 

        $datadetails = $request->request->get('datadetails');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        if ($id) {
            $decharge = $this->getDoctrine()
                    ->getRepository('AppBundle:Decharge')
                    ->find($id);
        } else {
            $decharge = new Decharge();
        }

        $decharge->setBeneficiaire($beneficiaire);
        if ($mode_paiement == 1) {
            $decharge->setCheque($cheque);
        } else if ($mode_paiement == 4) {
            $decharge->setCheque($carte_bancaire);
        }

  
        $decharge->setMontant($montant);
        $decharge->setRaison($raison);
        $decharge->setNumFacture($num_facture);
        $decharge->setTypePayement($type_payment);
        $decharge->setFournisseur($fournisseur);
        if ($mois_facture) {
            $decharge->setMoisFacture(\DateTime::createFromFormat('j/m/Y', '01/' . $mois_facture));
        } else {
            $decharge->setMoisFacture(null);
        }

        $decharge->setDate(\DateTime::createFromFormat('j/m/Y', $date));
        if ($date_cheque) {
            $decharge->setDateCheque(\DateTime::createFromFormat('j/m/Y', $date_cheque));
        } else {
            $decharge->setDateCheque(null);
        }

        if ($date_virement) {
            $decharge->setVirement($virement);
            $decharge->setDateVirement(\DateTime::createFromFormat('j/m/Y', $date_virement));
        } else {
            $decharge->setVirement($virement);
            $decharge->setDateVirement(null);
        } 

        if ($date_validation) {
            $decharge->setDateValidation(\DateTime::createFromFormat('j/m/Y', $date_validation));
        } else {
            $decharge->setDateValidation(null);
        }

        $decharge->setLettre($lettre);
        $decharge->setModePaiement($mode_paiement);
        $decharge->setDevise($devise);

        if ($service) {
            $service = $this->getDoctrine()
                    ->getRepository('AppBundle:MotifDecharge')
            ->find($service);

            $decharge->setMotifDecharge($service);
        }

        $decharge->setTypeMotif($motif);
        $decharge->setStatut($statut ? $statut : 1);

        $decharge->setAgence($agence);

        $em = $this->getDoctrine()->getManager();
        $em->persist($decharge);
        $em->flush();

        if ($id) {
            $logs = $this->get('app.logs');
            $user = $this->getUser();
            $logs->setStory($user,'Modification Décharge' . $decharge->getId());
        } else {
             $logs = $this->get('app.logs');
            $user = $this->getUser();
            $logs->setStory($user,'Création Décharge' . $decharge->getId());
        }

        if ($type_payment == 2) {
            $ech_achat_dep = new EcheanceAchatDepense();

            $ech_achat_dep->setIdDepense($decharge->getId());
            $ech_achat_dep->setMontant($montant_echeance_paye);
            $ech_achat_dep->setDateEch(new \DateTime);
            $ech_achat_dep->setCreatedAt(new \DateTime('now', new \DateTimeZone("+3")));
            $ech_achat_dep->setUpdatedAt(new \DateTime('now', new \DateTimeZone("+3")));

            $em->persist($ech_achat_dep);
            $em->flush();
        }

        $arr_id_dep = [];

        for ($i = 0; $i < count($datadetails); $i++) {
            $elem = $datadetails[$i];
            if ($id) {
                if ($elem[0] == 0) {
                    $detailsDepense = new DetailsDepense();
                    $detailsDepense->setIdDesignation($elem[1]);
                    $detailsDepense->setIdDecharge($decharge->getId());
                    $detailsDepense->setCreatedAt(new \DateTime('now', new \DateTimeZone("+3")));
                } else
                    $detailsDepense = $this->getDoctrine()
                        ->getRepository('AppBundle:DetailsDepense')
                        ->findOneBy(array(
                            "idDecharge" => $id,
                            "id" => $elem[0]
                        ));
                array_push($arr_id_dep, $elem[0]);
            } else {
                $detailsDepense = new DetailsDepense();
                $detailsDepense->setIdDesignation($elem[1]);
                $detailsDepense->setIdDecharge($decharge->getId());
                $detailsDepense->setCreatedAt(new \DateTime('now', new \DateTimeZone("+3")));
            }

            $detailsDepense->setDescription('');
            $detailsDepense->setQuantite($elem[2]);
            $detailsDepense->setPrixUnitaire($elem[3]);
            $detailsDepense->setUpdatedAt(new \DateTime('now', new \DateTimeZone("+3")));
            if ($elem[0] == 0)
                $em->persist($detailsDepense);
            $em->flush();
        }

        // if ($id) {
        //     $detailsDepense = $this->getDoctrine()
        //         ->getRepository('AppBundle:DetailsDepense')
        //         ->findBy(array(
        //             "idDecharge" => $id,
        //         ));

        //     foreach ($detailsDepense as $detailDep) {
        //         if (!in_array($detailDep->getId(), $arr_id_dep)) {
        //             $em->remove($detailDep);
        //             $em->flush();
        //         }
        //     }
        // }
        

        return new JsonResponse(array(
            'id' => $decharge->getId()
        ));
        
    }

    public function saveDesignationAction(Request $request)
    {

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
            ->getRepository('AppBundle:UserAgence')
            ->findOneBy(array(
                'user' => $user
            ));
        $agence = $userAgence->getAgence();
        $agenceId = $agence->getId();

        $em = $this->getDoctrine()->getManager();
        $designation = $request->request->get('designation');

        $table_design = new DesignationDepense();

        $table_design->setNom($designation);
        $table_design->setAgence($agenceId);
        $table_design->setCreatedAt(new \DateTime('now', new \DateTimeZone("+3")));
        $table_design->setUpdatedAt(new \DateTime('now', new \DateTimeZone("+3")));

        $em->persist($table_design);
        $em->flush();

        $listDesign = $this->getDoctrine()
            ->getRepository('AppBundle:DesignationDepense')
            ->findAll();

        return $this->render('ComptabiliteBundle:Decharge:designation.html.twig', array(
            "listDesign" => $listDesign
        ));
    }

    public function declareAction()
    {
        $user = $this->getUser();
        
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();
        
        $motifs = $this->getDoctrine()
                    ->getRepository('AppBundle:MotifDecharge')
                    ->findBy(array(
                        'agence' => $agence
                    ));

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
            ->getRepository('AppBundle:UserAgence')
            ->findOneBy(array(
                'user' => $user
            ));
        $agence = $userAgence->getAgence();

        $decharges = $this->getDoctrine()
            ->getRepository('AppBundle:Decharge')
            ->consultation($agence->getId());

        return $this->render('ComptabiliteBundle:Decharge:declare.html.twig',array(
            'motifs' => $motifs,
            'decharges' => $decharges
        ));
    }

    public function payementAchatAction(Request $request)
    {
        $id = $request->request->get('id');

        $decharges = $this->getDoctrine()
            ->getRepository('AppBundle:Decharge')
            ->getOneDepense($id);

        $allInfo["decharges"] = [];
        $allInfo["echeances"] = [];
        array_push($allInfo["decharges"], $decharges);

        $echeances = $this->getDoctrine()
            ->getRepository('AppBundle:EcheanceAchatDepense')
            ->getAllEcheanceByDep($id);

        array_push($allInfo["echeances"], $echeances);

        return new JsonResponse($allInfo);
    }

    public function listAction(Request $request)
    {

        // $decharges = $this->listDecharge($request);

        return new JsonResponse(array_merge($decharges));
    }

    public function listDecharge(Request $request)
    {
        $statut = $request->request->get('statut');
        $recherche_par = $request->request->get('recherche_par');
        $a_rechercher = $request->request->get('a_rechercher');
        $type_date = $request->request->get('type_date');
        $mois = $request->request->get('mois');
        $annee = $request->request->get('annee');
        $date_specifique = $request->request->get('date_specifique');
        $debut_date = $request->request->get('debut_date');
        $fin_date = $request->request->get('fin_date');
        $filtre_motif = $request->request->get('filtre_motif');

        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        $agence = $userAgence->getAgence();

        $decharges = $this->getDoctrine()
            ->getRepository('AppBundle:Decharge')
            ->consultation(
                $agence->getId(),
                $statut,
                $recherche_par,
                $a_rechercher,
                $type_date,
                $mois,
                $annee,
            $date_specifique, 
                $debut_date,
                $fin_date,
                $filtre_motif
            );

        return $decharges;
    }

    public function creationDesignationAction()
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
            ->getRepository('AppBundle:UserAgence')
            ->findOneBy(array(
                'user' => $user
            ));
        $agence = $userAgence->getAgence();
        $agenceId = $agence->getId();

        $listDesign = $this->getDoctrine()
            ->getRepository('AppBundle:DesignationDepense')
            ->findBy(array(
                'agence' => $agenceId
            ));

        return $this->render('ComptabiliteBundle:Decharge:designation.html.twig', array(
            "listDesign" => $listDesign
        ));
    }

    public function showAction($id)
    {
        $decharge = $this->getDoctrine()
                    ->getRepository('AppBundle:Decharge')
                    ->find($id);
        $agence = $decharge->getAgence();

        $motifs = $this->getDoctrine()
                    ->getRepository('AppBundle:MotifDecharge')
                    ->findBy(array(
                        'agence' => $agence
                    ));

        $detailsDepense = $this->getDoctrine()
            ->getRepository('AppBundle:DetailsDepense')
            ->findBy(array(
                "idDecharge" => $id
            ));
        
        return $this->render('ComptabiliteBundle:Decharge:show.html.twig',array(
            'agence' => $agence,
            'decharge' => $decharge,
            'motifs' => $motifs,
            "detailsDepense" => $detailsDepense,
           
        ));
    }

    public function pdfAction($id)
    {
        $decharge = $this->getDoctrine()
            ->getRepository('AppBundle:Decharge')
            ->find($id);

        $agence = $decharge->getAgence();

        $motifs = $this->getDoctrine()
                    ->getRepository('AppBundle:MotifDecharge')
                    ->findBy(array(
                        'agence' => $agence
                    ));

        $template = $this->renderView('ComptabiliteBundle:Decharge:pdf.html.twig',array(
            'decharge' => $decharge,
            'motifs' => $motifs,
        ));

        $html2pdf = $this->get('app.html2pdf');

        $html2pdf->create();

        return $html2pdf->generatePdf($template, "decharge" . $decharge->getId());
    }

    public function valideAction()
    {
        $user = $this->getUser();
        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
                    
        $agence = $userAgence->getAgence();
        
        $motifs = $this->getDoctrine()
                    ->getRepository('AppBundle:MotifDecharge')
                    ->findBy(array(
                        'agence' => $agence
                    ));
                    
        return $this->render('ComptabiliteBundle:Decharge:valide.html.twig',array(
            'motifs' => $motifs
        ));
    }

    public function validationAction($id)
    {

        $decharge = $this->getDoctrine()
                    ->getRepository('AppBundle:Decharge')
                    ->find($id);

        $dateValidation = new \DateTime('now');

        $decharge->setStatut(2);
        $decharge->setDateValidation($dateValidation);

        $em = $this->getDoctrine()->getManager();
        $em->persist($decharge);
        $em->flush();

        $logs = $this->get('app.logs');
        $user = $this->getUser();
        $logs->setStory($user,'Validation Décharge' . $decharge->getId());

        return new Response(200);
    }

    public function exportDeclareAction(Request $request)
    {
        $datas = json_decode(urldecode($request->request->get('datas')));
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $backgroundTitle = '808080';
        $phpExcelObject->getProperties()->setCreator("GAP Assurances")
            ->setLastModifiedBy("GAP Assurances")
            ->setTitle("Export excel Décharges déclarés")
            ->setSubject("Export excel Décharges déclarés")
            ->setDescription("Export excel Décharges déclarés")
            ->setKeywords("Décharges déclarés")
            ->setCategory("export excel");
        $sheet = $phpExcelObject->setActiveSheetIndex(0);


        $sheet->setCellValue('A1', 'GAP ASSURANCES');
        $sheet->setCellValue('A2', 'Décharges déclarés');

        /*Titre*/
        $sheet->setCellValue('A4', 'Bénéficiaire');
        $sheet->setCellValue('B4', 'N° chèque');
        $sheet->setCellValue('C4', 'Montant');
        $sheet->setCellValue('D4', 'Date déclaration');


        $sheet->getStyle('A4:D4')
            ->getFill()
            ->setFillType('solid')
            ->getStartColor()->setRGB('2b9902');

        foreach(range('A','D') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        $index = 5;
        $total = 0;

        foreach ($datas as $data) {
           $sheet->setCellValue('A'.$index,$data->beneficiaire); 
           $sheet->setCellValue('B'.$index,$data->cheque); 
           $sheet->setCellValue('C'.$index,$data->montant); 
           $sheet->setCellValue('D'.$index,$data->date); 

           $total += $data->montant;
           $index++;
        }

        $tindex = $index + 1;

        $sheet->setCellValue('A'.$tindex,'Total'); 
        $sheet->setCellValue('D'.$tindex,$total); 

        $sheet->getStyle('D'.$tindex)
            ->getFill()
            ->setFillType('solid')
            ->getStartColor()->setRGB('2b9902');


        $phpExcelObject->getActiveSheet()->setTitle('DECHARGES DECLARES');
        $phpExcelObject->setActiveSheetIndex(0);

        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);

        $ext = '.xls';

        $name = 'decharges-declares';

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

    public function exportValideAction(Request $request)
    {
        $datas = json_decode(urldecode($request->request->get('datas')));
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $backgroundTitle = '808080';
        $phpExcelObject->getProperties()->setCreator("GAP Assurances")
            ->setLastModifiedBy("GAP Assurances")
            ->setTitle("Export excel Décharges validés")
            ->setSubject("Export excel Décharges validés")
            ->setDescription("Export excel Décharges validés")
            ->setKeywords("Décharges validés")
            ->setCategory("export excel");
        $sheet = $phpExcelObject->setActiveSheetIndex(0);


        $sheet->setCellValue('A1', 'GAP ASSURANCES');
        $sheet->setCellValue('A2', 'Décharges validés');

        /*Titre*/
        $sheet->setCellValue('A4', 'Bénéficiaire');
        $sheet->setCellValue('B4', 'N° chèque');
        $sheet->setCellValue('C4', 'Montant');
        $sheet->setCellValue('D4', 'Date déclaration');


        $sheet->getStyle('A4:D4')
            ->getFill()
            ->setFillType('solid')
            ->getStartColor()->setRGB('2b9902');

        foreach(range('A','D') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        $index = 5;
        $total = 0;

        foreach ($datas as $data) {
           $sheet->setCellValue('A'.$index,$data->beneficiaire); 
           $sheet->setCellValue('B'.$index,$data->cheque); 
           $sheet->setCellValue('C'.$index,$data->montant); 
           $sheet->setCellValue('D'.$index,$data->date); 

           $total += $data->montant;
           $index++;
        }

        $tindex = $index + 1;

        $sheet->setCellValue('A'.$tindex,'Total'); 
        $sheet->setCellValue('D'.$tindex,$total); 

        $sheet->getStyle('D'.$tindex)
            ->getFill()
            ->setFillType('solid')
            ->getStartColor()->setRGB('2b9902');


        $phpExcelObject->getActiveSheet()->setTitle('DECHARGES VALIDES');
        $phpExcelObject->setActiveSheetIndex(0);

        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);

        $ext = '.xls';

        $name = 'decharges-valides';

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

    public function deleteAction($id)
    {
        $decharge  = $this->getDoctrine()
                        ->getRepository('AppBundle:Decharge')
                        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($decharge);
        $em->flush();

        return new JsonResponse(200);
    }

    public function validerPayementAchatAction(Request $request)
    {
        $idDepense = $request->request->get('idDepense');
        $date_paiement = $request->request->get('date_paiement');
        $montant = $request->request->get('montant');
        $montantTotalDep = $request->request->get('montantTotalDep');
        $ech_achat_dep = new EcheanceAchatDepense();

        if ($date_paiement == '')
            $date = new \DateTime;
        else
            $date = new \DateTimeImmutable($date_paiement);

        $ech_achat_dep->setIdDepense($idDepense);
        $ech_achat_dep->setMontant($montant);
        $ech_achat_dep->setDateEch($date);
        $ech_achat_dep->setCreatedAt(new \DateTime('now', new \DateTimeZone("+3")));
        $ech_achat_dep->setUpdatedAt(new \DateTime('now', new \DateTimeZone("+3")));

        $em = $this->getDoctrine()->getManager();
        $em->persist($ech_achat_dep);
        $em->flush();
        $result = [];
        $echeances = $this->getDoctrine()
            ->getRepository('AppBundle:EcheanceAchatDepense')
            ->getAllEcheanceByDep($idDepense);

        $result["echeances"] = $echeances;
        $result["totalDepense"] = $montantTotalDep;

        return new JsonResponse($result);
    }

}
