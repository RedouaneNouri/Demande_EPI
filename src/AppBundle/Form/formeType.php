<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
//dropdown menu (1..20)
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Entity\ligneD;
use AppBundle\Entity\lesDemandes;
use AppBundle\Entity\Techniciens;
use AppBundle\Entity\demandeur;
use AppBundle\Form\TechniciensType;
use AppBundle\Form\ligneType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class formeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('ligneD', CollectionType::class,array(
                'entry_type' => ligneType::class))
                ->add('technicien', EntityType::class,array(
                  'label' => 'Technicien bénéficiaire BT',
                  'class' => Techniciens::class,
                  'choice_label' => function ($technicien) {
                  /*  if ($technicien->isActif()== 1)
                    {*/
                      return $technicien->getNom().' '.$technicien->getPrenom().'       '.$technicien->getBt();
                    }
                  /*  else
                    {
                      return false;

                  }}*/ ,
                  'choices' => $options['techs']))

                  ->add('commentaire',  TextareaType::class,array(
                        'attr' => array('style' => 'width: 95%'),
                        'required' => false))

          ;

    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'techs' => array(),
            'lignes' => array(),
            'user' => array(),
            'epis' => array(),
            'data_class' => lesDemandes::class,

        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_epi';
    }


}
