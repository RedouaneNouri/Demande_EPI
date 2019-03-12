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


class AjouterEpiType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        //Ajouter epi
        $builder->add('refMaximo', NumberType::class, array('label' => 'Référence MAXIMO' , 'required'=>true, 'attr'=> array('type'=>'number')))
                    ->add('Description', TextareaType::class, array('required' => true))
                    ->add('refLyreco', TextType::class, array('label' => 'Référence Fournisseur', 'required'=>true))
                    ->add('puht', NumberType::class, array('label' => 'Prix UHT', 'required'=>true, 'attr'=> array('type'=>'number')))
                    ->add('ajouter_epi', SubmitType::class, array('label' => 'Ajouter un EPI'));
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
