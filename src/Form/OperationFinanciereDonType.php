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
            $form->add('operation', OperationFinanciereType::class)
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
                ->add('datelimitezakat',null, [ 
                    'widget' => 'single_text',
                ] )
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
