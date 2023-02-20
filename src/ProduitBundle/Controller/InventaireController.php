<?php

namespace ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class InventaireController extends Controller
{
	public function indexAction()
	{
        $agences  = $this->getDoctrine()
                        ->getRepository('AppBundle:Agence')
                        ->findAll();

        $user = $this->getUser();

        $userAgence = $this->getDoctrine()
                    ->getRepository('AppBundle:UserAgence')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        // $categories  = $this->getDoctrine()
        //                 ->getRepository('AppBundle:CategorieProduit')
        //                 ->findAll();

        $categories = [];

        $agence = $userAgence->getAgence();

        $preference = $this->getDoctrine()
                ->getRepository('AppBundle:PreferenceCategorie')
                ->findOneBy(array(
                    'agence' => $agence
                ));

        if ($preference) {
            $categoriesList = $preference->getCategoriesList();

            foreach ($categoriesList as $id) {
                $item = $this->getDoctrine()
                    ->getRepository('AppBundle:CategorieProduit')
                    ->find($id);
                array_push($categories, $item);
            }
        }

        // $categories = $this->getDoctrine()
        //     ->getRepository('AppBundle:CategorieProduit')
        //     ->findAll();

        $entrepots = $this->getDoctrine()
                ->getRepository('AppBundle:Entrepot')
                ->findBy(array(
                    'agence' => $agence
                ));

        $userEntrepot = $this->getDoctrine()
                    ->getRepository('AppBundle:UserEntrepot')
                    ->findOneBy(array(
                        'user' => $user
                    ));
        
        return $this->render('ProduitBundle:Inventaire:index.html.twig', array(
            'agences' => $agences,
            'userAgence' => $userAgence,
            'categories' => $categories,
            'entrepots' => $entrepots,
            'userEntrepot' => $userEntrepot
        ));
	}

    public function listAction(Request $request)
    {
        $agence = $request->request->get('agence');
        $entrepot = $request->request->get('entrepot');
        $recherche_par = $request->request->get('recherche_par');
        $a_rechercher = $request->request->get('a_rechercher');
        $categorie = $request->request->get('categorie');

        $results  = $this->getDoctrine()
                        ->getRepository('AppBundle:VariationProduit')
                        ->list(
                        	$agence,
                            $recherche_par,
                            $a_rechercher,
                            $categorie,
                            $entrepot
                        );

        return new JsonResponse($results);
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

        // $backgroundTitle = '808080';
        $phpExcelObject->getProperties()->setCreator("SHISSAB")
            ->setLastModifiedBy("SHISSAB")
            ->setTitle("Export excel inventaire")
            ->setSubject("Export excel inventaire")
            ->setDescription("Export excel inventaire")
            ->setKeywords("inventaire")
            ->setCategory("export excel");
        $sheet = $phpExcelObject->setActiveSheetIndex(0);


        $sheet->setCellValue('A1', $agence->getNom());
        $sheet->setCellValue('A2', 'Inventaire');

        /*Titre*/
        $sheet->setCellValue('A4', 'Entrepot');
        $sheet->setCellValue('B4', 'Catégorie');
        $sheet->setCellValue('C4', 'Nom');
        $sheet->setCellValue('D4', 'Prix de vente');
        $sheet->setCellValue('E4', 'Stock');
        $sheet->setCellValue('F4', 'Total');
        // $sheet->setCellValue('G4', 'Personne concerné');
        // $sheet->setCellValue('H4', 'Montant');

        $sheet->getStyle('A4:F4')
            ->getFill()
            ->setFillType('solid')
            ->getStartColor()->setRGB('c0c0c0');


        foreach(range('A','F') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        

        $index = 5;

        $totalGeneral = 0; 
        $totalStock = 0 ;

        foreach ($datas as $data) {
           $sheet->setCellValue('A'.$index,$data->entrepot); 
           $sheet->setCellValue('B'.$index,$data->categorie); 
           $sheet->setCellValue('C'.$index,$data->nom); 
           $sheet->setCellValue('D'.$index,$data->prix_vente); 
           $sheet->setCellValue('E'.$index,$data->stock); 
           $sheet->setCellValue('F'.$index,$data->total); 
        //    $sheet->setCellValue('G'.$index,$data->op_nom); 
        //    $sheet->setCellValue('H'.$index,$data->montant); 

        //    if ($data->operation == 'Retrait') {
        //        $sheet->getStyle('A' . $index . ':H' . $index)
        //         ->getFill()
        //         ->setFillType('solid')
        //         ->getStartColor()->setRGB('ed55651a');
        //    } else {
        //         $sheet->getStyle('A' . $index . ':H' . $index)
        //         ->getFill()
        //         ->setFillType('solid')
        //         ->getStartColor()->setRGB('2b99021a');
        //    }
            $totalStock += $data->stock ;
            $totalGeneral += $data->total;
            $index++;
        }

        $tindex = $index + 1;

        $sheet->setCellValue('A'.$tindex,'Total General'); 
        $sheet->setCellValue('E'.$tindex, $totalStock); 
        $sheet->setCellValue('F'.$tindex,$totalGeneral); 

        // $sheet->getStyle('H'.$tindex)
        //     ->getFill()
        //     ->setFillType('solid')
        //     ->getStartColor()->setRGB('b8e4bb');

        $phpExcelObject->getActiveSheet()->setTitle('INVENTAIRE');
        $phpExcelObject->setActiveSheetIndex(0);

        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);

        $ext = '.xls';

        $name = 'inventaire';

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
}
