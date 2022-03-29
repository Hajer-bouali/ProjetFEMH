<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date',null, [ 
                'widget' => 'single_text',
            ])
            ->add('quantite')
            ->add('montent')
            ->add('bondachat')
            ->add('quantiteviande')
            ->add('quantitelaine')
            ->add('numerodossier')
            ->add('beneficiairecouche')
            ->add('quantitecouche')
            ->add('taillecouche')
            ->add('telbeneficiairecouche')
            ->add('cinbeneficiairecouche')
            ->add('adherent')
            ->add('typeEvenement')
            ->add('Enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
