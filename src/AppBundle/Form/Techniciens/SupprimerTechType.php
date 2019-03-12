<?php

namespace AppBundle\Form\Techniciens;

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


class SupprimerTechType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Supprimer technicien
        $builder->add('tech', EntityType::class, array(
            'label' => 'Techniciens',
            'class' => Techniciens::class,
            'choice_label' => function ($tech) {
                return $tech->getNom().' '.$tech->getPrenom().'    '.$tech->getBt();
            },
            'query_builder' => function (TechniciensRepository $repo) {
                return $repo->createQueryBuilder('t')->where('t.actif =1');
            }
               ))

                ->add('supprimer_technicien', SubmitType::class, array('label' => 'Supprimer un technicien'));
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
