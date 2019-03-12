<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Entity\ligneD;
use AppBundle\Entity\lesDemandes;
use AppBundle\Entity\Techniciens;
use AppBundle\Entity\demandeur;
use AppBundle\Form\TechniciensType;
use AppBundle\Form\ligneType;
use AppBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class FilterDemandeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('technicien', EntityType::class, array(
            'attr' => array('style' => 'width: 250px'),
            'placeholder' => 'sélectionner un technicien',
            'required' => false,
            'expanded' => false,
            'multiple' => false,
            'label' => 'Selectionner un technicien ',
            'class' => Techniciens::class,
            'choice_label' => function ($techniciens) {
                return $techniciens->getNom().'  '.$techniciens->getPrenom() ;
            }

          ))
                ->add('demandeur', EntityType::class, array(
            'attr' => array('style' => 'width: 255px'),
            'placeholder' => 'sélectionner un demandeur',
            'required' => false,
            'label' => 'Selectionner un demandeur ',
            'class' => User::class,
            'choice_label' => function ($demandeurs) {
                return $demandeurs->getNom().'  '.$demandeurs->getPrenom() ;
            },

            'choices' => null))


            ->add('date', DateType::class, array(
            //    'attr' => array('style' => 'width: 100px'),
                'required' => false,
                'widget' => 'single_text',
                'html5' => true,
              ))

              ->add('statut', ChoiceType::class, array(
                  'attr' => array('style' => 'width: 250px'),
                  'placeholder' => 'sélectionner un statut',
                  'required' => false,
                  'label' => 'Selectionner un un statut ',
                  'choices' => array(
                   'À traiter' => 0,
                   'Validée' => 1,
                   'Refusée' => 2,
                   'Expédiée' => 3,
               ),
                ))

               ->add('chercher', SubmitType::class, array(
                 'label' => 'Chercher'
               ));


        ;
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
            // 'tech' => Techniciens::class
        ));
    }
}
