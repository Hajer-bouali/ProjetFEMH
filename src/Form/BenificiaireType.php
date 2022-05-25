<?php

namespace App\Form;

use App\Entity\Benificiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;



class BenificiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('relationfamiliale', ChoiceType::class, [
                'choices' => [
                    'Conjoint'=> 'Conjoint',
                    'Pére'=>'Pére' ,
                    'Mére'=>'Mére' ,
                    'Fils'=>'Fils' ,
                    'Fille'=>'Fille'
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'relation familiale' 
            ])
            ->add('nom')
            ->add('datenaissance',null, [ 
                'widget' => 'single_text',
            ])
            ->add('niveauetude', ChoiceType::class, [
                'choices' => [
                    'Jardin d enfants'=> 'Jardin d enfants',
                    '1 ere annee de base'=>'1 ere annee de base',
                    '2 eme annee de base'=>'2 annee de base', 
                    '3 eme annee de base'=>'3 annee de base',
                    '4 eme annee de base'=>'4 annee de base',
                    '5 eme annee de base'=>'5 annee de base',
                    '6 eme annee de base'=>'6 annee de base',
                    '7 eme annee de base'=>'7 annee de base',
                    '8 eme annee de base'=>'8 annee de base',
                    '9 eme annee de base'=>'9 annee de base',
                    '1 er année secondaire'=>'1er année secondaire',
                    '2 er année secondaire'=>'2er année secondaire',
                    '3 er année secondaire'=>'3er année secondaire',
                    '4 er année secondaire'=>'4er année secondaire',
                    'Faculté'=>'Faculté',
                    'N étudie pas actuellement'=>'N étudie pas actuellement'                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'Niveau d etude' 
            ])
            ->add('metier')
            //->add('adherent')
            ->add('Enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Benificiaire::class,
        ]);
    }
}
