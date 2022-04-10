<?php

namespace App\Form;

use App\Entity\OperationFinanciereDon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperationFinanciereDonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // On modifie le formulaire avant de définir les datas
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            //On récupère l'entité lié au formulaire
            $entity = $event->getData();
            $form = $event->getForm();
            $form->add('etat', ChoiceType::class, [
                'choices' => [
                    'Demande' => 'demande',
                    'Valide' => 'valide',
                    'Refuse' => 'refuse',
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'etat',
                'data' => $entity->getEtat() ? $entity->getEtat() : 'Demande',
                'label' => 'etat',
            ])
                ->add('nomdonataire')
                ->add('montant')
                ->add('modepaiement', ChoiceType::class, [
                    'choices' => [
                        'Espèce' => 'espece',
                        'Chèque' => 'cheque',
                        'Virement bancaire' => 'virementbancaire',
                        'Prélèvement bancaire' => 'virementbancaire',
                    ],
                    'expanded' => false,
                    'multiple' => false,
                    'label' => 'typeadherent',
                ])
                ->add('date', null, [
                    'widget' => 'single_text',
                ])
                ->add('responsable')
                ->add('typeadherent', ChoiceType::class, [
                    'choices' => [
                        'Orpholin' => 'Orpholin',
                        'Sociale' => 'Sociale',
                        'santé' => 'sante',
                        'Etudiant' => 'etudiant',
                        'Veuves' => 'veuves',
                        'Autre' => 'autre',
                    ],
                    'expanded' => false,
                    'multiple' => false,
                    'label' => 'typeadherent',
                ])
                ->add('stipulation', ChoiceType::class, [
                    'choices' => [
                        'sadaka' => 'Sadaka',
                        'zakat' => 'Zakat',
                        'abonnement' => 'Abonnement',
                    ],
                    'expanded' => false,
                    'multiple' => false,
                    'label' => 'stipulation',
                ])
                ->add('caisse')
                ->add('Enregistrer', SubmitType::class)
            ;
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OperationFinanciereDon::class,
        ]);
    }
}
