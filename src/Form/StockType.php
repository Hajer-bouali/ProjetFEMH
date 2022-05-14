<?php

namespace App\Form;

use App\Entity\Stock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantite')
            ->add('produit')
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
            ->add('prixunitaire')
            ->add('Enregistrer', SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stock::class,
        ]);
    }
}
