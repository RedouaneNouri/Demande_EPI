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

// use Symfony\Bridge\Doctrine\Form\Type\


class AjouterTechType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


            // Ajouter technicien
        $builder ->add('nom', TextType::class, array('label' => 'Nom','required'=>true))
                    ->add('prenom', TextType::class, array('label' => 'Prénom','required'=>true))
                    ->add('BT', NumberType::class, array('label' => 'BT','required'=>true, ))
                    ->add('ajouter_technicien', SubmitType::class, array('label' => 'Ajouter un technicien'));
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
