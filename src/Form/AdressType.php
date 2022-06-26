<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use App\Entity\Adress;



class AdressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class, [
                'label' => "Nom de l'adresse",
                'attr' => [
                    'placeholder' => 'Entrer le nom de votre adresse'
                ],
                'required' => true,
            ])
            ->add('firstname',TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Entrer votre prénom'
                ],
                'required' => true,
            ])
            ->add('lastname',TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Entrer votre nom'
                ],
                'required' => true,
            ])
            ->add('company',TextType::class, [
                'label' => 'Société',
                'attr' => [
                    'placeholder' => '(Facultatif)Entrer le nom de votre société'
                ],
                'required' => false,
            ])
            ->add('adress',TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => 'Entrer votre adresse'
                ],
                'required' => true,
            ])
            ->add('postal',TextType::class, [
                'label' => 'Code postal',
                'attr' => [
                    'placeholder' => 'Entrer votre code postal'
                ],
                'required' => true,
            ])
            ->add('city',TextType::class, [
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Entrer le nom de votre ville'
                ],
                'required' => true,
            ])
            ->add('country',CountryType::class, [
                'label' => 'Pays',
                'attr' => [
                    'placeholder' => 'Votre pays',
                    'class' => 'form-control'
                ],
                'required' => true,
            ])
            ->add('phone',TelType::class, [
                'label' => 'Téléphone',
                'attr' => [
                    'placeholder' => 'Entrer votre numéro de téléphone'
                ],
                'required' => true,
            ])
            ->add ('save', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btn-block btn-info',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adress::class,
        ]);
    }
}
