<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameWorkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\epi;
use AppBundle\Entity\Techniciens;
use AppBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\demandeur;
use AppBundle\Entity\ligneD;
use AppBundle\Entity\lesDemandes;
use AppBundle\Form\ligneType;
use AppBundle\Form\formeType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use AppBundle\Repository\UserRepository;
use AppBundle\Form\FilterDemandeType;

// use Starnes\UserBundle\Entity\User;
// use Starnes\UserBundle\Entity\User;

// use app\Resources\views\Emails\DemandeEnvoyee;
// use app\Resources\views\Emails\DemandeReçue;

class formController extends Controller
{

        /**
         *
         *@Route("/demande", name="demande")
         *
         *@return \Symfony\Component\HttpFoundation\Response
         */
    public function formeAction(Request $request, \Swift_Mailer $mailer)
    {

        //get User
        if ($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
        }




        //recuperation epi
        $entityManager = $this->getDoctrine()->getManager();
        $repo  = $entityManager->getRepository(epi::class);
        $epis = $repo->findBy(array('actif' => 1), array('description' => 'ASC'));
        //recuperation tecniciens
        $repo2 = $entityManager->getRepository(Techniciens::class);
        $techs =  $repo2->findBy(array('actif' => 1), array('nom' => 'ASC'));

        //recuperation tecniciens
        $repo3 = $entityManager->getRepository(User::class);
        $valideurs =  $repo3->findByRole('ROLE_VALIDEUR');


        foreach ($valideurs as $valideur) {
            $valideursEmails[] = $valideur->getEmail();
        }


        $demandeur = $this->getUser();

        $demande = new lesDemandes();
        $demande->setDemandeur($demandeur);
        foreach ($epis as $epi) {
            $d = new ligneD();
            $d->setEpi($epi);
            $demande->addLigneD($d);
        }

        //get form
        $form = $this->createForm(formeType::class, $demande, (array('techs'=>$techs,'user'=>$demandeur)));
        //add the submit button
        $form->add('save', SubmitType::class, array('label' => 'Envoyer'));

        $form->handleRequest($request);

        //generate html code for the form
        $formView = $form->createView();

        //Submit
        $ligneD = $demande->getLigneD();
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated

            $demandes = $form->getData();
            $okPersist = false;
            $aid = true;
            $okQte = false;


            foreach ($ligneD as $ligne) {
                if (($ligne->getSelection())) {
                    if (ctype_digit($ligne->getQuantite()) && ((int)$ligne->getQuantite() <= 100) && ((int)$ligne->getQuantite()>0)) {
                        $okQte = true;
                    } else {
                        $okQte = false;
                        $request->getSession()->getFlashBag()->add('warning', 'La quantité doit être une valeur entière entre 1 et 100');
                        //	return $this->redirectToRoute('demande');
                    }

                    $aid = $aid && $okQte;
                }
            }
            foreach ($ligneD as $ligne) {
                if (!($ligne->getSelection())) {
                    $demandes->removeLigneD($ligne);
                } else {
                    $okPersist=true;
                }
            }
            if ($okPersist &&  $aid) {
                //persist in database
                $entityManager->persist($demandes);
                $entityManager->flush();

                //create Email confirmation to "demandeur"
                $messageToDemandeur = (new \Swift_Message('Confirmation Email'))
                                ->setFrom('no-reply.demande-epi-pprd@airliquide.com')
                                ->setTo($user->getEmail())
                                ->setBody(
                                          $this->renderView(
                                                  // app/Resources/views/Emails/registration.html.twig
                                                  'Emails/DemandeEnvoyee.html.twig',
                                                  array('user'=>$demandeur
                                                ,'demande' => $demandes)
                                  ),
                                  'text/html'
                                );
                //create Email confirmation to "valideur"
                $messageToValideur = (new \Swift_Message('Notification Email'))
                                ->setFrom('no-reply.demande-epi-pprd@airliquide.com')
                                ->setTo($valideursEmails)
                                ->setBody(
                                          $this->renderView(
                                                  // app/Resources/views/Emails/registration.html.twig
                                                  'Emails/DemandeRe.html.twig',
                                                  array('user'=>$demandeur
                                                ,'demande' => $demandes)
                                  ),
                                  'text/html'
                                );



                //send Email confirmation to "demandeur"
                $mailer->send($messageToDemandeur);
                //send Email confirmation to "valideur"
                $mailer->send($messageToValideur);
                //output sending confirmation
                $request->getSession()->getFlashBag()->add('notice', 'Demande bien envoyée');
                //redirection for new "demande"
                return $this->redirectToRoute('demande');
            } elseif ($okPersist != true) {
                $request->getSession()->getFlashBag()->add('warning', 'selectionnez au moins un EPI');
                //return $this->redirectToRoute('demande');
            }
        }
        //send view
        return $this->render('epiDemande.html.twig', array('form'=>$formView,'user'=>$demandeur));
    }


    /**
     *
         *@Route("/listeDemande", name="listeDemande")
     *
     *@return \Symfony\Component\HttpFoundation\Response
     */
    public function listeDemandeAction(Request $request)
    {
        //get "lesdemandes" from repository
        $repository = $this->getDoctrine()->getRepository('AppBundle:lesDemandes');
        $allDemande = $repository->getEpiWithDemande();
        $dates = $repository->getDatesAllDemandes();

        $demandeur = $this->get('security.token_storage')->getToken()->getUser();
        //get technicien from repository
        $techRipository = $this->getDoctrine()->getRepository('AppBundle:Techniciens');
        $techniciens = $techRipository->findAll();
        //get the users from repository
        $repo3 = $this->getDoctrine()->getRepository('AppBundle:User');
        $demandeurs =  $repo3->findAll();



        $form = $this->createForm(FilterDemandeType::class, array('allDemande'=>$allDemande,'users'=>$demandeurs, 'techniciens' => $techniciens,'dates'=> $dates ));
        $form->handleRequest($request);



        //generate html code for the form
        $formView = $form->createView();

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
  
            $allDemande = $repository->getDateBetween2($post['date'], $post['technicien'], $post['demandeur'], $post['statut']);

            return $this->render('listeDemande.html.twig', array('form'=>$formView,'allDemande'=>$allDemande,'user'=>$demandeur ));
        }

        return $this->render('listeDemande.html.twig', array('form'=>$formView,'allDemande'=>$allDemande));
    }
}
