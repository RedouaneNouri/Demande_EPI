<?php

namespace AppBundle\Chart;

use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\lesDemandes;
use AppBundle\Entity\epi;


class ChartData
{
    private $em;



    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }
    public function nbEpiInDemandes()
    {
      $em = $this->getDoctrine()->getManager();


      //*****************************************************

      $arrayToDataTable[] = ['description', 'Nombre de demandes'];


      $repo  = $em->getRepository(epi::class);
      $epis = $repo->findAll();

      $repo2  = $em->getRepository(lesDemandes::class);
      $demandes = $repo2->findAll();

      $repo3  = $em->getRepository(ligneD::class);
      $lignes = $repo3->findAll();


      $nbTotalEpi = 0;

      foreach($epis as $k=>$epi)
        {
         $nbEpiByEpi[$k] = 0;
        }

      foreach($epis as $key=>$epi)
        {

          foreach($lignes as $ligne)
            {
              if($epi === ($ligne->getEpi()))
              {
                //$lesEpis[$key]= $epi->getDescription();
                $nbEpiByEpi[$key]++;
               // $nbTotalEpi ++;
              }
            }
          $arrayToDataTable[] = [$epi->getDescription(),$nbEpiByEpi[$key]];
        }

    }



}
