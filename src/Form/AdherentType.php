<?php

namespace App\Form;
use App\Entity\Adherent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class AdherentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // On modifie le formulaire avant de définir les datas
    $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
        //On récupère l'entité lié au formulaire
        $entity = $event->getData();
        $form = $event->getForm();
        $form->add('piecesjointes', FileType::class,[
            'label'=>false,
            'multiple'=>true,
            'mapped'=>false,
            'required'=>false,
            'attr' =>['class'=>'form-input-styled']
        ])
            
            ->add('nom')
            ->add('cin')
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
           // ->add('benificiaires')
           /*->add('etatreunion', ChoiceType::class, [
            'choices' => [
                'en cour'=>'en cour',
                'valide'=>'valide',
                'refuse'=>'refuse',
                'reporte'=>'reporte',
                 
            ],
            'expanded' => false,
            'multiple' => false,
            'label' => 'etat de dossier' 
        ])*/
            ->add('logement', ChoiceType::class, [
                'choices' => [
                    'Locataire '=>'Locataire',
                    'Propriétaire'=>'Propriétaire' 
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'logement' 
            ])
            ->add('prixlocation', TextType::class, [
                'required' => false])
            ->add('nombrechambre')
            ->add('electricite', ChoiceType::class, [
                'choices' => [
                    'Oui'=>'oui',
                    'Non'=>'non'
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'electricite' 
            ])
            ->add('eau', ChoiceType::class, [
                'choices' => [
                    'Oui'=>'oui',
                    'Non'=>'non'
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'eau' 
            ])
           // ->add('installationnondisponible')
            ->add('handicap', ChoiceType::class, [
                'choices' => [
                    'Oui'=>'oui',
                    'Non'=>'non'
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'handicap' 
            ])
            ->add('typehandicap', TextType::class, [
                'required' => false])
           /* ->add('famillehandicap', ChoiceType::class, [
                'choices' => [
                    'Oui'=>0,
                    'Non'=>1 
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'famillehandicap' 
            ])*/
            ->add('maladiechronique', ChoiceType::class, [
                'choices' => [
                    'Oui'=>'oui',
                    'Non'=>'non' 
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'maladiechronique' 
            ])
            ->add('typemaladiechronique', TextType::class, [
                'required' => false])
            ->add('montantrevenu')
            ->add('source')
            ->add('resume', TextareaType::class,[
                'attr' =>['class'=>'form-control'],
                'label' => 'resume' 
            ])
            ->add('demande')
            ->add('typeadherent')
            

           // ->add('Enregistrer', SubmitType::class)

        ;
    });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adherent::class,
        ]);
    }
}