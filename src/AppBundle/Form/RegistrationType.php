<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class RegistrationType extends AbstractType
{
   public function buildForm(FormBuilderInterface $builder, array $options)

   {
       $builder->add('prenom', TextType::class, array('label' => 'Prénom'));
       $builder->add('nom');
       $builder->add('roles', ChoiceType::class, array(
    'choices'  => array(
      //  'IS_AUTHENTICATED_ANONYMOUSLY' => 'IS_AUTHENTICATED_ANONYMOUSLY',
        'Administrateur' => 'ROLE_ADMIN',
        'Valideur' => 'ROLE_VALIDEUR',
        'Utilisateur standard' => 'ROLE_STANDARD'
    ),
    'choice_name' => function ($value, $key, $index) {
          return $value;
          },
          'multiple' => true,
          'expanded' => true,
    ));

   }

   public function getParent()

   {
       return 'FOS\UserBundle\Form\Type\RegistrationFormType';
   }

   public function getBlockPrefix()

   {
       return 'app_user_registration';
   }

   public function getNom()

   {
       return $this->getBlockPrefix();
   }

}
