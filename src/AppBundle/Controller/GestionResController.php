<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameWorkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\epi;
use AppBundle\Entity\User;
use AppBundle\Entity\Techniciens;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\GestionResType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use AppBundle\Repository\epiRepository;
use AppBundle\Repository\UserRepository;
use AppBundle\Repository\TechniciensRepository;
use Symfony\Bridge\Doctrine\Form\Type\TechniciensType;
use AppBundle\Form\Techniciens\AjouterTechType;
use AppBundle\Form\Techniciens\SupprimerTechType;
use AppBundle\Form\Epi\SupprimerEpiType;
use AppBundle\Form\Epi\AjouterEpiType;
use AppBundle\Form\Users\DisableUserType;
use AppBundle\Form\Users\EnableUserType;

class GestionResController extends Controller
{
    /**
     *
     *@Route("/gestionRessources", name="gestionRessources")
     *
     *@return \Symfony\Component\HttpFoundation\Response
     */
    public function gestionAction(Request $request)
    {
        //recuperation epi
        $entityManager = $this->getDoctrine()->getManager();
        $repoEpi = $entityManager->getRepository(epi::class);
        $epis = $repoEpi->findAll();

        // recuperation tecniciens
        $repoTech = $entityManager->getRepository(Techniciens::class);
        $techniciens =  $repoTech->findBy(array('actif' => 1), array('nom' => 'ASC'));

        // recuperation Users
        $repoUser = $entityManager->getRepository(User::class);
        $usersEnabled =  $repoUser->findBy(array('enabled' => 1), array('nom' => 'ASC'));
        $usersDisabled = $repoUser->findBy(array('enabled' => 0), array('nom' => 'ASC'));

        $forms= $this->createForms($techniciens, $epis, $usersEnabled, $usersDisabled);

        foreach ($forms as $nameform => $form) {
            $form->handleRequest($request);
        }


        $params = $this->generateParams($forms, $techniciens, $epis, $usersEnabled, $usersDisabled);
        //generate html code for the form


        return $this->render('gestionRes.html.twig', $params);
    }


    //creating all the forms
    public function createForms($techniciens, $epis, $usersEnabled, $usersDisabled)
    {
        //array of forms
        $forms = array();
        //global form
        $forms['form'] = $this->createForm(GestionResType::class, (array('technicien'=>$techniciens,'epis'=>$epis, 'usersDis'=>$usersEnabled, 'usersEn'=>$usersDisabled)));
        //technicien add form
        $forms['formTechAdd'] = $this->createForm(AjouterTechType::class, null, array(
            'action' => $this->generateUrl('gestionActionTechAdd'),
            'method' => 'POST'))  ;
        //technicien Del form
        $forms['formTechDel'] = $this->createForm(SupprimerTechType::class, null, array(
            'action' => $this->generateUrl('gestionActionTechDel'),
            'method' => 'POST'))  ;
        //add epi form
        $forms['formEpiAdd'] = $this->createForm(AjouterEpiType::class, null, array(
                  'action' => $this->generateUrl('gestionActionEpiAdd'),
                  'method' => 'POST'))  ;
        //epi deletion form
        $forms['formEpiDel'] = $this->createForm(SupprimerEpiType::class, null, array(
                      'action' => $this->generateUrl('gestionActionEpiDel'),
                      'method' => 'POST'))  ;

        //user disable forms
        $forms['formUserDisable'] = $this->createForm(DisableUserType::class, null, array(
                      'action' => $this->generateUrl('gestionActionUserDisable'),
                      'method' => 'POST'))  ;

        //user enable forms
        $forms['formUserEnable'] = $this->createForm(EnableUserType::class, null, array(
                      'action' => $this->generateUrl('gestionActionUserEnable'),
                      'method' => 'POST'))  ;

        //get forms
        return $forms;
    }
    public function generateParams($forms, $techniciens, $epis, $usersEnabled, $usersDisabled)
    {
        $params = array();
        foreach ($forms as $nameform => $form) {
            $params[$nameform] = $form->createView();
        }
        $params['technicien'] = $techniciens;
        $params['epi'] = $epis;
        $params['usersEnabled'] = $usersEnabled;
        $params['usersDisabled'] = $usersDisabled;
        return $params;
    }

    /**
     *
     *@Route("/gestionActionUserDisable", name="gestionActionUserDisable")
     *
     *@return \Symfony\Component\HttpFoundation\Response
     */
    public function gestionActionUserDisable(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        //recuperation epi
        $repoEpi = $entityManager->getRepository(epi::class);
        $epis = $repoEpi->findAll();
        // recuperation tecniciens
        $repoTech = $entityManager->getRepository(Techniciens::class);
        $techniciens =  $repoTech->findBy(array('actif' => 1), array('nom' => 'ASC'));

        // recuperation Users
        $repoUser = $entityManager->getRepository(User::class);
        $usersEnabled =  $repoUser->findBy(array('enabled' => 1), array('nom' => 'ASC'));
        $usersDisabled = $repoUser->findBy(array('enabled' => 0), array('nom' => 'ASC'));

        $forms = $this->createForms($techniciens, $epis, $usersEnabled, $usersDisabled);

        $forms['formUserDisable']->handleRequest($request);

        if ($forms['formUserDisable']->isSubmitted() && $forms['formUserDisable']->isValid()) {
            //	 $techDel = new Techniciens();
            $postUser = $forms['formUserDisable']->getData();
            $userDis = $postUser['usersDis'];

            foreach ($usersEnabled as $userEnabled) {
                if ($userDis == $userEnabled) {
                    if (!(null === $userEnabled || (empty($userEnabled) && '0' != $userEnabled))) {
                        $userEnabled->setEnabled(0);
                        $entityManager->persist($userEnabled);
                        $entityManager->flush();
                        $request->getSession()->getFlashBag()->add('notice', 'Modification bien enregistrée');
                        return $this->redirectToRoute('gestionRessources');
                    }
                }
            }
        }


        return $this->forward('AppBundle:GestionRes:gestion', $this->generateParams($forms, $techniciens, $epis, $usersEnabled, $usersDisabled));
    }

    /**
     *
     *@Route("/gestionActionUserEnable", name="gestionActionUserEnable")
     *
     *@return \Symfony\Component\HttpFoundation\Response
     */
    public function gestionActionUserEnable(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        //recuperation epi
        $repoEpi = $entityManager->getRepository(epi::class);
        $epis = $repoEpi->findAll();
        // recuperation tecniciens
        $repoTech = $entityManager->getRepository(Techniciens::class);
        $techniciens =  $repoTech->findBy(array('actif' => 1), array('nom' => 'ASC'));

        // recuperation Users
        $repoUser = $entityManager->getRepository(User::class);
        $usersEnabled =  $repoUser->findBy(array('enabled' => 1), array('nom' => 'ASC'));
        $usersDisabled = $repoUser->findBy(array('enabled' => 0), array('nom' => 'ASC'));

        $forms = $this->createForms($techniciens, $epis, $usersEnabled, $usersDisabled);

        $forms['formUserEnable']->handleRequest($request);


        if ($forms['formUserEnable']->isSubmitted() && $forms['formUserEnable']->isValid()) {
            $postUser = $forms['formUserEnable']->getData();
            $userDis = $postUser['usersEn'];

            foreach ($usersDisabled as $userDisabled) {
                if ($userDis == $userDisabled) {
                    if (!(null === $userDisabled || (empty($userDisabled) && '0' != $userDisabled))) {
                        $userDisabled->setEnabled(1);
                        $entityManager->persist($userDisabled);
                        $entityManager->flush();
                        $request->getSession()->getFlashBag()->add('notice', 'Modification bien enregistrée');
                        return $this->redirectToRoute('gestionRessources');
                    }
                }
            }
        } else {
            $request->getSession()->getFlashBag()->add('warning', 'Une erreure s\'est produite');
            // return $this->forward('AppBundle:GestionRes:gestion', $this->generateParams($forms, $techniciens, $epis, $usersEnabled, $usersDisabled));
        }


        return $this->forward('AppBundle:GestionRes:gestion', $this->generateParams($forms, $techniciens, $epis, $usersEnabled, $usersDisabled));
    }


    /**
     *
     *@Route("/gestionActionTechAdd", name="gestionActionTechAdd")
     *
     *@return \Symfony\Component\HttpFoundation\Response
     */
    public function gestionActionTechAdd(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        //recuperation epi
        $repoEpi = $entityManager->getRepository(epi::class);
        $epis = $repoEpi->findAll();
        // recuperation tecniciens
        $repoTech = $entityManager->getRepository(Techniciens::class);
        $techniciens =  $repoTech->findBy(array('actif' => 1), array('nom' => 'ASC'));

        // recuperation Users
        $repoUser = $entityManager->getRepository(User::class);
        $usersEnabled =  $repoUser->findBy(array('enabled' => 1), array('nom' => 'ASC'));
        $usersDisabled = $repoUser->findBy(array('enabled' => 0), array('nom' => 'ASC'));

        $forms = $this->createForms($techniciens, $epis, $usersEnabled, $usersDisabled);

        $forms['formTechAdd']->handleRequest($request);

        if ($forms['formTechAdd']->isSubmitted() && $forms['formTechAdd']->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $post = $forms['formTechAdd']->getData();
            $tech = new Techniciens();
            $techExists = $repoTech->findOneBy(['bT' => $post['BT']]);
            if ($techExists != null) {
                $request->getSession()->getFlashBag()->add('warning', 'Numéro de BT déja existant');
            } else {
                $tech->setNom($post['nom']);
                $tech->setPrenom($post['prenom']) ;
                $tech->setBT($post['BT']) ;
                // add item to Database
                $entityManager->persist($tech);
                $entityManager->flush();
                // retrieve messages
                $request->getSession()->getFlashBag()->add('notice', 'Modification bien enregistrée');
                return $this->redirectToRoute('gestionRessources');
            }
        }

        return $this->forward('AppBundle:GestionRes:gestion', $this->generateParams($forms, $techniciens, $epis, $usersEnabled, $usersDisabled));
    }


    /**
     *
     *@Route("/gestionActionTechDel", name="gestionActionTechDel")
     *
     *@return \Symfony\Component\HttpFoundation\Response
     */
    public function gestionActionTechDel(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        //recuperation epi
        $repoEpi = $entityManager->getRepository(epi::class);
        $epis = $repoEpi->findAll();
        // recuperation tecniciens
        $repoTech = $entityManager->getRepository(Techniciens::class);
        $techniciens =  $repoTech->findBy(array('actif' => 1), array('nom' => 'ASC'));

        // recuperation Users
        $repoUser = $entityManager->getRepository(User::class);
        $usersEnabled =  $repoUser->findBy(array('enabled' => 1), array('nom' => 'ASC'));
        $usersDisabled = $repoUser->findBy(array('enabled' => 0), array('nom' => 'ASC'));

        $forms = $this->createForms($techniciens, $epis, $usersEnabled, $usersDisabled);

        $forms['formTechDel']->handleRequest($request);

        if ($forms['formTechDel']->isSubmitted() && $forms['formTechDel']->isValid()) {
            //	 $techDel = new Techniciens();
            $post2 = $forms['formTechDel']->getData();
            $techDel = $post2['tech'];

            foreach ($techniciens as $techni) {
                if ($techDel == $techni) {
                    if (!(null === $techni || (empty($techni) && '0' != $techni))) {
                        $techni->setInactif();
                        $entityManager->persist($techni);
                        $entityManager->flush();
                        $request->getSession()->getFlashBag()->add('notice', 'Modification bien enregistrée');
                        return $this->redirectToRoute('gestionRessources');
                    }
                }
            }
        }
        $request->getSession()->getFlashBag()->add('warning', 'Une erreure s\'est produite');
        return $this->forward('AppBundle:GestionRes:gestion', $this->generateParams($forms, $techniciens, $epis, $usersEnabled, $usersDisabled));
    }



    /**
     *
     *@Route("/gestionActionEpiAdd", name="gestionActionEpiAdd")
     *
     *@return \Symfony\Component\HttpFoundation\Response
     */
    public function gestionActionEpiAdd(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        //recuperation epi
        $repoEpi = $entityManager->getRepository(epi::class);
        $epis = $repoEpi->findBy(array('actif' => 1), array('description' => 'ASC'));
        // recuperation tecniciens
        $repoTech = $entityManager->getRepository(Techniciens::class);
        $techniciens =  $repoTech->findBy(array('actif' => 1), array('nom' => 'ASC'));

        // recuperation Users
        $repoUser = $entityManager->getRepository(User::class);
        $usersEnabled =  $repoUser->findBy(array('enabled' => 1), array('nom' => 'ASC'));
        $usersDisabled = $repoUser->findBy(array('enabled' => 0), array('nom' => 'ASC'));

        $forms = $this->createForms($techniciens, $epis, $users);

        $forms['formEpiAdd']->handleRequest($request);

        if ($forms['formEpiAdd']->isSubmitted() && $forms['formEpiAdd']->isValid()) {
            // add item to Database
            $post = $forms['formEpiAdd']->getData();
            $epiExists = $repoEpi->findOneBy(['refMaximo' => $post['refMaximo']]);
            if ($epiExists != null) {
                $request->getSession()->getFlashBag()->add('warning', 'Réference Maximo d\'EPI existe déja');
            } else {
                $epi = new epi();
                $epi->setActif();
                $epi->setRefMaximo($post['refMaximo']);
                $epi->setDescription($post['Description']) ;
                $epi->setRefLyreco($post['refLyreco']) ;
                $epi->setPuht($post['puht']) ;
                $entityManager->persist($epi);
                $entityManager->flush();
                // retrieve messages
                $request->getSession()->getFlashBag()->add('notice', 'Modification bien enregistrée');
                return $this->redirectToRoute('gestionRessources');
            }
        }
        return $this->forward('AppBundle:GestionRes:gestion', $this->generateParams($forms, $techniciens, $epis, $usersEnabled, $usersDisabled));
    }

    /**
     *
     *@Route("/gestionActionEpiDel", name="gestionActionEpiDel")
     *
     *@return \Symfony\Component\HttpFoundation\Response
     */
    public function gestionActionEpiDel(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        //recuperation epi
        $repoEpi = $entityManager->getRepository(epi::class);
        $epis = $repoEpi->findAll();
        // recuperation tecniciens
        $repoTech = $entityManager->getRepository(Techniciens::class);
        $techniciens =  $repoTech->findBy(array('actif' => 1), array('nom' => 'ASC'));

        // recuperation Users
        $repoUser = $entityManager->getRepository(User::class);
        $usersEnabled =  $repoUser->findBy(array('enabled' => 1), array('nom' => 'ASC'));
        $usersDisabled = $repoUser->findBy(array('enabled' => 0), array('nom' => 'ASC'));

        $forms = $this->createForms($techniciens, $epis, $users);

        $forms['formEpiDel']->handleRequest($request);

        if ($forms['formEpiDel']->isSubmitted() && $forms['formEpiDel']->isValid()) {
            $post2 = $forms['formEpiDel']->getData();
            $epiDel = $post2['epi'];
            if (!(false === $epiDel || (empty($epiDel) && '0' != $epiDel))) {
                //$repo = $entityManager->getRepository(epi::class);
                //$entityManager->remove($epiDel);
                $epiDel->setInactif();
                $entityManager->persist($epiDel);
                $entityManager->flush();
                $request->getSession()->getFlashBag()->add('notice', 'Modification bien enregistrée');
                return $this->redirectToRoute('gestionRessources');
            }
            return $this->forward('AppBundle:GestionRes:gestion', $this->generateParams($forms, $techniciens, $epis, $usersEnabled, $usersDisabled));
        }
    }
}
