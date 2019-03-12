<?php

namespace AppBundle\Controller;

//use Symfony\Component\Routing\Annotation\Route;
    use Sensio\Bundle\FrameWorkExtraBundle\Configuration\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Request;
    use AppBundle\Entity\ligneD;
    use AppBundle\Form\valideType;
    use AppBundle\Entity\lesDemandes;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
    use AppBundle\Repository\UserRepository;
    use AppBundle\Entity\User;

    class valideController extends Controller
    {

    /**
     *
     * @Route("/aValider", name="aValider")
     *
     */

        public function validAction(Request $request)
        {
            $id = $_GET['id'];
            $entityManager = $this->getDoctrine()->getManager();
            $repo2 = $entityManager->getRepository(lesDemandes::class);
            //get the selected epi
            $demande =  $repo2->findById(array('id'=>$id));

            return $this->render('epiValide.html.twig', array('id'=>$id,'demande'=>$demande));
        }




        public function sendNotification($to, $template, $commentaire, $demande, \Swift_Mailer $mailer)
        {

              //create Email
            $message = (new \Swift_Message('Notification Email'))
                                          ->setFrom('no-reply.demande-epi-pprd@airliquide.com')
                                          ->setTo($to)
                                          ->setBody(
                                                              $this->renderView(
                                                                        // 'Emails/DemandeValidee.html.twig',
                                                                      $template,
                                                                     array('commentaire'=>$commentaire
                                                                          ,'demande' => $demande[0])
                                              ),
                                              'text/html'
                                          );

            //send Email notification "accepté" to "demandeur"
            $mailer->send($message);
        }


        /**
         *
         * @Route("/accept", name="accept")
         *
         */
        public function acceptAction(Request $request, \Swift_Mailer $mailer)
        {

                        //get entity getManager
            $entityManager = $this->getDoctrine()->getManager();
            //get the "valideur" Users
            $repo3 = $entityManager->getRepository(User::class);
            $valideurs =  $repo3->findByRole('ROLE_VALIDEUR');
            $valideursEmails;

            //get the demandeur
            if ($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
                $user = $this->container->get('security.token_storage')->getToken()->getUser();
            }


            //bring their emails to an array
            foreach ($valideurs as $valideur) {
                $valideursEmails[] = $valideur->getEmail();
            }

            //get "les demandes"
            $entityManager = $this->getDoctrine()->getManager();
            $repo2 = $entityManager->getRepository(lesDemandes::class);

            //id of specific "demande"
            $demandeId = $_POST['demandeId'];
            $demande = new lesDemandes();
            //get the selected demande
            $demande =  $repo2->findById(array('id'=>$demandeId));

            //get the demandeur email
            $demandeurEmail = $demande[0]->getDemandeur()->getEmail();
            //get the valideur comment
            $commentaire = $_POST['commentaire'];
            //if button "accepter" is pushed

            if (isset($_POST['accepter'])) {
                //validate will bring "valid" parameter to 1*/
                $demande[0]->setValide(1);
                $entityManager->persist($demande[0]);
                $entityManager->flush();

                //create Email confirmation to "valideurs"
                $this->sendNotification($valideursEmails, 'Emails/DemandeValidee.html.twig', $commentaire, $demande, $mailer);
                //create Email confirmation to "demandeur"
                $this->sendNotification($demandeurEmail, 'Emails/DemandeValidee.html.twig', $commentaire, $demande, $mailer);

                return $this->redirectToRoute('listeDemande');
            } elseif (isset($_POST['refuser'])) {
                //refuser will bring "valid" parameter to 2*/
                $demande[0]->setValide(2);
                $entityManager->persist($demande[0]);
                $entityManager->flush();




                //create and send Email confirmation to "demandeur"
                $this->sendNotification($valideursEmails, 'Emails/DemandeRefusee.html.twig', $commentaire, $demande, $mailer);
                //create and send Email confirmation to "valideur"
                $this->sendNotification($demandeurEmail, 'Emails/DemandeRefusee.html.twig', $commentaire, $demande, $mailer);

                return $this->redirectToRoute('listeDemande');
            } elseif (isset($_POST['expedier'])) {
                //expedier will bring "valid" parameter to 3*/
                $demande[0]->setValide(3);
                $entityManager->persist($demande[0]);
                $entityManager->flush();

                //cretae email "expédiée" for "valideurs"
                $this->sendNotification($valideursEmails, 'Emails/DemandeExpediee.html.twig', $commentaire, $demande, $mailer);
                //reate email "expédiée" for "demandeur"
                $this->sendNotification($demandeurEmail, 'Emails/DemandeExpediee.html.twig', $commentaire, $demande, $mailer);

                return $this->redirectToRoute('listeDemande');
            }
        }

        /**
         *
         * @Route("/print", name="print")
         *
         */
        public function printAction(Request $request)
        {
            $id = $_GET['id'];
            $entityManager = $this->getDoctrine()->getManager();
            $repo2 = $entityManager->getRepository(lesDemandes::class);
            //get the selected epi
            $demande =  $repo2->findById(array('id'=>$id));
            return $this->render('print.html.twig', array('id'=>$id,'demande'=>$demande));
        }
    }
