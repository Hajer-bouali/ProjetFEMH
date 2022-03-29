<?php

namespace App\Form;
use App\Entity\Adherent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class AdherentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('piecesjointes', FileType::class,[
            'label'=>false,
            'multiple'=>true,
            'mapped'=>false,
            'required'=>false,
            'attr' =>['class'=>'form-input-styled']
        ])
            ->add('numero')
            ->add('nom')
            ->add('cin')
            ->add('nomconjoint')
            ->add('cinconjoint')
            ->add('adresse')
            ->add('telephone')
            ->add('etatcivil', ChoiceType::class, [
                'choices' => [
                    'Célibataire'=> 'Celibataire',
                    'Marié'=>'Marie' ,
                    'Divorcé'=>'Divorce' ,
                    'veuf(ve)'=>'veuf'
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'etat civil' 
            ])
            ->add('nombrefamille')
            ->add('logement', ChoiceType::class, [
                'choices' => [
                    'Louer une maison'=>'Louer',
                    'posseder une maison'=>'posseder' 
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'logement' 
            ])
            ->add('prixlocation')
            ->add('nombrechambre')
            ->add('electricite', ChoiceType::class, [
                'choices' => [
                    'Oui'=>0,
                    'Non'=>1
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'electricite' 
            ])
            ->add('eau', ChoiceType::class, [
                'choices' => [
                    'Oui'=>0,
                    'Non'=>1 
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'eau' 
            ])
            ->add('installationnondisponible')
            ->add('handicap', ChoiceType::class, [
                'choices' => [
                    'Oui'=>0,
                    'Non'=>1 
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'handicap' 
            ])
            ->add('typehandicap')
            ->add('famillehandicap', ChoiceType::class, [
                'choices' => [
                    'Oui'=>0,
                    'Non'=>1 
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'famillehandicap' 
            ])
            ->add('maladiechronique', ChoiceType::class, [
                'choices' => [
                    'Oui'=>0,
                    'Non'=>1 
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'maladiechronique' 
            ])
            ->add('typemaladiechronique')
            ->add('montantrevenu')
            ->add('source')
            ->add('resume', TextareaType::class,[
                'attr' =>['class'=>'form-control'],
                'label' => 'resume' 
            ])
            ->add('demande')
            ->add('quienregistrefichier')
            
            

            ->add('Enregistrer', SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adherent::class,
        ]);
    }
}