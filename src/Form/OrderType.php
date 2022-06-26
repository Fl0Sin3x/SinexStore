<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use App\Entity\Carrier;
use App\Entity\Adress;


class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //dd($options);
        $user = $options['user'];
        $builder
            ->add('adresses', EntityType::class, [
                'label' => false,
                'required' => true,
                'class' => Adress::class,
                'choices' => $user->getAdresses(),
                'multiple' => false,
                'expanded' => true,

            ])
            ->add('carriers', EntityType::class, [
                'label' => "Transporteur",
                'required' => true,
                'class' => Carrier::class,
                'multiple' => false,
                'expanded' => true,

            ])
            ->add('save', SubmitType::class, [
                'label' => 'Valider la commande',
                'attr' => [
                    'class' => 'btn btn-success btn-block'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user'=> array()
        ]);
    }
}
