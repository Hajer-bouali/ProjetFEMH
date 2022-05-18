<?php

namespace App\Form;

use App\Entity\Revenufamilial;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class RevenufamilialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('benificiaire')
            ->add('affairessociales')
            ->add('typecarte', ChoiceType::class, [
                'choices' => [
                    'Vert'=>'Vert',
                    'Jaune'=>'Jaune',
                    'Blanc'=>'Blanc' 
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'Type de couleur de Carné' 
            ])
            ->add('numinscription')
            ->add('regimesecuritesociale', ChoiceType::class, [
                'choices' => [
                    'CNSS'=>'CNSS',
                    'CNRPS'=>'CNSS' 
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'Le régime de sécurité sociale' 
            ])
            ->add('typeregimesecuritesociale', ChoiceType::class, [
                'choices' => [
                    'Remboursement des dépenses'=>'Remboursement des dépenses',
                    'médecin de famille'=>'médecin de famille' 
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'Type de régime du sécurité sociale' 
            ])
            //->add('adherent')
            ->add('Enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Revenufamilial::class,
        ]);
    }
}
