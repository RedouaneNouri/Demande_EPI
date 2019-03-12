<?php

namespace AppBundle\Controller;


	use Sensio\Bundle\FrameWorkExtraBundle\Configuration\Route;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use AppBundle\Entity\Techniciens;
	use AppBundle\Form\TechniciensType;

	class techController extends Controller

	{

		/**
		 *
		 *@Route("/techdemande", name="tdemandeEPI")
		 *
		 *@return \Symfony\Component\HttpFoundation\Response
		 */
		public function techAction(){

			$entityManager = $this->getDoctrine()->getManager();
			$repo2 = $entityManager->getRepository(Techniciens::class);
			//generate new  tecniciens
			$techs =  $repo2->findAll();

			//get form
		$form = $this->createForm(TechniciensType::class,null,(array('techs'=> $techs)));
			//generate html code for the form
			$formView = $form->createView();


			//send view
			return $this->render('techDemande.html.twig', array('form'=>$formView));

		}


	}

	?>
