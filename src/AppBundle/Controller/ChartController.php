<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\ligneD;
use AppBundle\Entity\lesDemandes;
use AppBundle\Entity\epi;
use AppBundle\Entity\Techniciens;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ComboChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ColumnChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Options\VAxis;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use AppBundle\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChartController extends Controller
{
    /**
     * @Route("chart", name="chart")
     */
    public function chartAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        //*****************************************************

        $arrayToDataTable[] = ['description', 'Nombre de demandes'];

        //get all EPI from the repository
        $repo  = $em->getRepository(epi::class);
        $epis = $repo->findAll();

        //get all demande from the repository
        $repo2  = $em->getRepository(lesDemandes::class);
        $demandes = $repo2->findAll();

        //get all lignes from the repository
        $repo3  = $em->getRepository(ligneD::class);
        $lignes = $repo3->findAll();

        //get all Techniciens from the repository
        $repo4 = $em->getRepository(Techniciens::class);
        $techniciens = $repo4->findAll();

        //get the user
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $nbTotalEpi = 0;

        //initialisation of $nbEpiByEpi array
        foreach ($epis as $k=>$epi) {
            //this array will contain how much time each EPI is in demande
            $nbEpiByEpi[$k] = 0;
        }
        //the following instructions count how much time was taken each EPI
        foreach ($epis as $key=>$epi) {
            foreach ($lignes as $ligne) {
                if ($epi === ($ligne->getEpi())) {
                    $nbEpiByEpi[$key] += $ligne->getQuantite();
                    // $nbTotalEpi ++;
                }
            }
            $arrayToDataTable[] = [$epi->getDescription(),$nbEpiByEpi[$key]];
        }



        //initialisation of $nbEpiByTechnicien array
        foreach ($techniciens as $i=>$technicien) {
            //this array will contain how much time each Tehcnicien is in demande
            $nbEpiByTechnicien[$i] = 0;
        }

        $arrayToDataTable2[] = ['techniciens','n° demandes epis'];

        //the following instructions count number of EPI per Technicien
        foreach ($techniciens as $j=>$technicien) {
            $nbEpiByTechnicien[$i] = 0;

            foreach ($demandes as $demande) {
                $lignes = $demande->getLigneD();
                if ($technicien === ($demande->getTechnicien())) {
                    foreach ($lignes as $ligne) {
                        //$lesEpis[$key]= $epi->getDescription();
                        $nbEpiByTechnicien[$j] += $ligne->getQuantite();
                        // $nbTotalEpi ++;
                    }
                }
            }
            $arrayToDataTable2[] = [$technicien->getNom().' '.$technicien->getPrenom(),$nbEpiByTechnicien[$j]];
        }


        /*Epi By Mounth*/



        $arrayToDataTable3[] = ['Mois','n° epis'];
        $mois = ['Janvier','Fevrier','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Decembre'];



        for ($d=1 ; $d <= 12; $d++) {
            $nbEpiByDate[] = 0;
        }
        //the following instructions count number of EPI by Mounth
        foreach ($mois as $d=> $moi) {
            foreach ($demandes as $demande) {
                $date=$demande->getDate();

                if ($date->format('m') == ''.($d+1)) {
                    foreach ($demande->getLigneD() as $ligne) {
                        $nbEpiByDate[$d] += $ligne->getQuantite();
                    }
                }
            }
            $arrayToDataTable3[] = [$moi,$nbEpiByDate[$d]];
        }

        //*********************************************************
        //                         Charts
        //*********************************************************


        $col = new ColumnChart();
        $col->getData()->setArrayToDataTable($arrayToDataTable3);
        $col->getOptions()->setTitle('Nombre d\'EPI par mois');
        $col->getOptions()->getAnnotations()->setAlwaysOutside(true);
        $col->getOptions()->getAnnotations()->getTextStyle()->setFontSize(14);
        $col->getOptions()->getAnnotations()->getTextStyle()->setColor('#000');
        $col->getOptions()->getAnnotations()->getTextStyle()->setAuraColor('none');
        $col->getOptions()->getHAxis()->setTitle('Mois');
        // $col->getOptions()->getHAxis()->setFormat('h:mm a');
        // $col->getOptions()->getHAxis()->getViewWindow()->setMin([7, 30, 0]);
        // $col->getOptions()->getHAxis()->getViewWindow()->setMax([17, 30, 0]);
        $col->getOptions()->getVAxis()->setTitle('Nombre d\'EPIs');
        // $col->getOptions()->setWidth(900);
        // $col->getOptions()->setHeight(500);*/

        $chart = new ComboChart();
        $chart->getData()->setArrayToDataTable($arrayToDataTable2);
        $vAxisAmount = new VAxis();
        $vAxisAmount->setTitle('Nombre de demandes');

        $chart->getOptions()->setVAxes([$vAxisAmount]);

        $seriesAmount = new \CMEN\GoogleChartsBundle\GoogleCharts\Options\ComboChart\Series();
        $seriesAmount->setType('bars');
        $seriesAmount->setTargetAxisIndex(0);

        $chart->getOptions()->setSeries([$seriesAmount]);
        $chart->getOptions()->setHeight(500);

        $chart->getOptions()->getHAxis()->setTitle('Tehcnicien');
        $chart->getOptions()->setColors(['#f6dc12']);//, '#759e1a'

        /****************************************************************/

        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable($arrayToDataTable);
        $pieChart->getOptions()->getLegend()->setPosition('yes');
        $pieChart->getOptions()->setPieSliceText('yes');
        $pieChart->getOptions()->setPieStartAngle(135);

        //other possible option on this chart :
        /*
        $pieSlice1 = new PieSlice();
        $pieSlice1->setColor('yellow');
        $pieSlice2 = new PieSlice();
        $pieSlice2->setColor('transparent');
        $pieChart->getOptions()->setSlices([$pieSlice1, $pieSlice2]);
        */
        // $pieChart->getOptions()->setHeight(500);
        // $pieChart->getOptions()->setWidth(650);
        $pieChart->getOptions()->getTooltip()->setTrigger('yes');
        $pieChart->getOptions()->setTitle('Nombre de demandes par EPI');








        if (isset($_POST['EpiByTechButton'])) {
            //create spreadsheet EpiByTech
            $spreadsheetEpiByTech = $this->get('phpoffice.spreadsheet')->createSpreadsheet();

            //$existingXlsx   = $this->get('phpoffice.spreadsheet')->createSpreadsheet('C:/Users/koceila.talbi/file.xlsx');
            foreach ($techniciens as $j=>$technicien) {
                $spreadsheetEpiByTech->getActiveSheet()->setCellValue('A'.$j, $technicien->getNom());
                $spreadsheetEpiByTech->getActiveSheet()->setCellValue('B'.$j, $technicien->getPrenom());
                $spreadsheetEpiByTech->getActiveSheet()->setCellValue('C'.$j, $nbEpiByTechnicien[$j]);
            }
            //Write if file EpiByTech
            $writerXlsx = $this->get('phpoffice.spreadsheet')->createWriter($spreadsheetEpiByTech, 'Xlsx');
            $dossier = 'download_excel';
            if (!is_dir($dossier)) {
                mkdir($dossier, 0777);
            }
            $writerXlsx->save('download_excel/EpiByTech.xlsx');

            //Download of the file


            //return $this->file('download_excel\EpiByTech.xlsx');

            $response =  new StreamedResponse(
           function () use ($writerXlsx) {
               $writerXlsx->save('php://output');
           }
       );
            $response->headers->set('Content-Type', 'application/vnd.ms-excel');
            $response->headers->set('Content-Disposition', 'attachment;filename="EpiByTech.xlsx"');
            $response->headers->set('Cache-Control', 'max-age=0');
            return $response;
        }

        if (isset($_POST['EpiByEpiButton'])) {
            //create spreadsheet EpiByEpi
            $spreadsheetEpiByEpi = $this->get('phpoffice.spreadsheet')->createSpreadsheet();
            foreach ($epis as $key=>$epi) {
                $spreadsheetEpiByEpi->getActiveSheet()->setCellValue('A'.$key, $epi->getRefMaximo());
                $spreadsheetEpiByEpi->getActiveSheet()->setCellValue('B'.$key, $epi->getDescription());
                $spreadsheetEpiByEpi->getActiveSheet()->setCellValue('C'.$key, $nbEpiByEpi[$key]);
            }

            //Write in file EpiByEpi
            $writerXlsx = $this->get('phpoffice.spreadsheet')->createWriter($spreadsheetEpiByEpi, 'Xlsx');

            $dossier = 'download_excel';
            if (!is_dir($dossier)) {
                mkdir($dossier);
            }


            $writerXlsx->save('download_excel/EpiByEpi.xlsx');
            //Download the file
            $response =  new StreamedResponse(
           function () use ($writerXlsx) {
               $writerXlsx->save('php://output');
           }
       );
            $response->headers->set('Content-Type', 'application/vnd.ms-excel');
            $response->headers->set('Content-Disposition', 'attachment;filename="EpiByEpi.xlsx"');
            $response->headers->set('Cache-Control', 'max-age=0');
            return $response;
            // other code but work only >= php 5.6.19
            //  return $this->file('download_excel\EpiByEpi.xlsx');
        }

        if (isset($_POST['EpiByDateButton'])) {

                          //create spreadsheet EpiByDate
            $spreadsheetEpiByDate = $this->get('phpoffice.spreadsheet')->createSpreadsheet();
            foreach ($mois as $key=>$moi) {
                $spreadsheetEpiByDate->getActiveSheet()->setCellValue('A'.$key, $moi);
                $spreadsheetEpiByDate->getActiveSheet()->setCellValue('B'.$key, $nbEpiByDate[$key]);
            }

            //Write in file EpiByEpi
            $writerXlsx = $this->get('phpoffice.spreadsheet')->createWriter($spreadsheetEpiByDate, 'Xlsx');

            $dossier = 'download_excel';
            if (!is_dir($dossier)) {
                mkdir($dossier);
            }


            $writerXlsx->save('download_excel/EpiByDate.xlsx');
            //Download the file
            
            //other code wotk only with versions >= php 5.6.19
            //return $this->file('download_excel\EpiByDate.xlsx');

            $response =  new StreamedResponse(
           function () use ($writerXlsx) {
               $writerXlsx->save('php://output');
           }
       );
            $response->headers->set('Content-Type', 'application/vnd.ms-excel');
            $response->headers->set('Content-Disposition', 'attachment;filename="EpiByDate.xlsx"');
            $response->headers->set('Cache-Control', 'max-age=0');
            return $response;
        }

        return $this->render('epiByEpiChart.html.twig', ['chart' => $chart, 'pieChart' => $pieChart,
        'col' => $col,'epis' =>$epis, 'nbEpiByEpi' => $nbEpiByEpi, 'techniciens' => $techniciens,
          'nbEpiByTechnicien' => $nbEpiByTechnicien, 'mois' => $mois,'nbEpiByDate' => $nbEpiByDate]);
    }
}
