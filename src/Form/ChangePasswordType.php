<?php

namespace App\Form;

use App\Entity\User;


use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled' => 'true',
                'label' => 'Mon adresse email'
            ])
            ->add('firstname',TextType::class, [
                'disabled' => 'true',
                'label' => 'Mon prénom'
            ])
            ->add('lastname',TextType::class, [
                'disabled' => 'true',
                'label' => 'Mon nom'
            ])
            ->add('old_password',PasswordType::class, [
                'label' => 'Mon mot de passe actuelle',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Veuillez saisir votre mot de passe actuel'
                ]
            ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne sont pas identiques',
                'label' => 'Votre nouveau Mot de passe',
                'mapped' => false,
                'required' => true,
                'first_options' => [
                    'label' => 'Nouveau mot de passe',
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe',
                ],

            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Mettre a jour mon mot de passe',
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
