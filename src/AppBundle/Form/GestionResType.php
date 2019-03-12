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
use AppBundle\Entity\Techniciens;
use AppBundle\Entity\epi;
use AppBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Repository\TechniciensRepository;
use Doctrine\ORM\EntityRepository;
use AppBundle\Repository\epiRepository;
use AppBundle\Repository\UserRepository;

// use Symfony\Bridge\Doctrine\Form\Type\


class GestionResType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    }



    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
             //'technicien' => Techniciens::class,
            // 'epis' => epi::class,
            'technicien' => array(),
            'epis' => array(),
            'usersEn' => array(),
            'usersDis' => array(),
            'data_class' => null,
            // 'tech' => Techniciens::class
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
