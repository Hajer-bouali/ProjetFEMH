<?php

namespace App\Form;

use App\Entity\OperationFinanciere;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperationFinanciereTypeDon extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // On modifie le formulaire avant de définir les datas
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            //On récupère l'entité lié au formulaire
            $entity = $event->getData();
            $formDon = $event->getForm();
            $formDon
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
                ->add('caisse')
                
                ->add('montant')
                ->add('datelimitezakat', null, [
                    'widget' => 'single_text',
                    ])
                ->add('modepaiement', ChoiceType::class, [
                    'choices' => [
                        'Espèce' => 'espece',
                        'Chèque' => 'cheque',
                    ],
                    'expanded' => false,
                    'multiple' => false,
                    'label' => 'typeadherent',
                ])
                /*->add('date', null, [
            'widget' => 'single_text',
            ])
            ->add('responsable')*/
                ->add('etat', ChoiceType::class, [
                    'choices' => [
                        'Demande' => 'demande',
                        'Valide' => 'valide',
                        'Refuse' => 'refuse',
                    ],
                    'expanded' => false,
                    'multiple' => false,
                    'label' => 'etat',
                    'data' => $entity && $entity->getEtat() ? $entity->getEtat() : 'Demande',

                ])
                ->add('evenement')
                ->add('pieceJointeOperations', FileType::class, [
                    'label'=> false,
                    'multiple'=> true,
                    'mapped'=> false,
                    'required'=> false,
                ])
                ->add('Enregistrer', SubmitType::class)
            ;
        });
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OperationFinanciere::class,
        ]);
    }
}
