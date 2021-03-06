<?php
namespace AppBundle\Form\Users;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\User;

class EnableUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('usersEn', EntityType::class, array(
            'label' => 'Utilisateurs ',
            'class' => User::class,
            'choice_label' => function ($utilisateurs) {
                return $utilisateurs->getNom().' '.$utilisateurs->getPrenom() ;
            },
            'query_builder' => function (UserRepository $repo) {
                return $repo->createQueryBuilder('t')->where('t.enabled = 0');
            },

            'choices' => null))
               ->add('activer_utilisateur', SubmitType::class, array('label' => 'Réactiver un compte'));


        ;
    }
}
