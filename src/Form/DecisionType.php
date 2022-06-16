<?php

namespace App\Form;

use App\Entity\Decision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DecisionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('adherent', EntityType::class, [
            'class' =>Adherent::class,
            'multiple' =>true,
        ])
        ->add('statut', ChoiceType::class, [
            'choices' => [
                'En cours' => 'Encours',
                'Valide' => 'valide',
                'Refuse' => 'refuse',
                'Reporte' => 'reporte',
            ],
            'expanded' => true,
        ])
            ->add('detail')
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Decision::class,
        ]);
    }
}
