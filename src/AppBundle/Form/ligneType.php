<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
//dropdown menu (1..20)
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\Entity\ligneD;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
class ligneType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder

        ->add('selection', CheckboxType::class, array(
          'label' => ' ',
          'required' => false))


        ->add('quantite', TextType::class,array(
          'label' => ' ',
          'attr' => array('style' => 'width: 50px', 'min' => 1, 'max' => 100),
          'data' => 1,
          'required' => false))

        ->add('taille', TextType::class,array(
          'label' => ' ',
          'attr' => array('style' => 'width: 50px'),

          'required' => false))

    ;}

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ligneD::class,

        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_quantite';
    }


}
