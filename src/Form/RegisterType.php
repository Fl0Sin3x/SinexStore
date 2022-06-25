<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use App\Entity\User;




class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom',

            ])

            ->add('firstname' , TextType::class, [
                'label' => 'Prénom',

            ])

            ->add('email', EmailType::class, [
                'label' => 'Email',

            ])
            ->add('password' , PasswordType::class, [
                'label' => 'Veuillez saisir votre Mot de passe',

            ])
            ->add('password_confirm', PasswordType::class, [
                'label' => 'Confirmation du mot de passe',
                'mapped' => false,

            ])
            ->add('submit', SubmitType::class, [
                'label' => 'S\'inscrire',

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
