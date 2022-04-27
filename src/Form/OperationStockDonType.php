<?php

namespace App\Form;

use App\Entity\OperationStock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperationStockDonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('datelimitezakat',null, [ 
                'widget' => 'single_text',
            ])
            ->add('nomdonataire')
            ->add('stipulation', ChoiceType::class, [
                'choices' => [
                    'sadaka' => 'Sadaka',
                    'zakat' => 'Zakat',
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'stipulation',
            ])
            ->add('unite', ChoiceType::class, [
                'choices' => [
                    'kg (Kilogramme)' => 'kilograme',
                    'L (Litre)' => 'litre',
                    'm (Métre)' => 'metre',
                    'Piece' => 'piece',
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'unite',
            ])
            ->add('evenement')
            ->add('Enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OperationStock::class,
        ]);
    }
}
