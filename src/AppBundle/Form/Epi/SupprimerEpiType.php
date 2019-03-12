<?php

namespace AppBundle\Form\Epi;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Entity\Techniciens;
use AppBundle\Entity\epi;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Repository\TechniciensRepository;
use Doctrine\ORM\EntityRepository;
use AppBundle\Repository\epiRepository;

// use Symfony\Bridge\Doctrine\Form\Type\


class SupprimerEpiType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('epi', EntityType::class, array(
              'label' => 'EPI ',
              'class' => epi::class,
              'choice_label' => function ($epi) {
                  return $epi->getRefMaximo().' '.$epi->getDescription().' '.$epi->getRefLyreco().''.$epi->getPuht() ;
              },
              'query_builder' => function (epiRepository $repo) {
                  return $repo->createQueryBuilder('t')->where('t.actif =1');
              },

              'choices' => null))
                 ->add('supprimer_epi', SubmitType::class, array('label' => 'Supprimer un EPI'));


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
