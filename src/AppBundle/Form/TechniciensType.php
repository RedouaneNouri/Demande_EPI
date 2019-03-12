<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
//dropdown menu (1..20)
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class TechniciensType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {



        $builder
        ->add('techs', EntityType::class,array(
          'class' => 'AppBundle:Techniciens',
          'choices' => $options['techs'],
        ))
          ;
          /*
          $demandes = array();

              foreach ($options['techs'] as $Techniciens){

                  $d = new DemandeOF();
                  $d->setEpi($Techniciens);

                  $demandes[]=$d;
              }
          

            $builder->add('epis', CollectionType::class,array(
                'entry_type' => demandeType::class,
                'data' => $demandes

            ));
              */

}/**
 * {@inheritdoc}
 */
public function configureOptions(OptionsResolver $resolver)
{
    $resolver->setDefaults(array(
        'data_class' => 'AppBundle\Entity\Techniciens',
        'choice_label' => 'id_tech',
        'techs' => array()
    ));
}

/**
 * {@inheritdoc}
 */
public function getBlockPrefix()
{
    return 'appbundle_techniciens';
}


}
