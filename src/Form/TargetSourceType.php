<?php

namespace App\Form;

use App\Entity\TargetSource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TargetSourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('domain', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom de domaine'
                ],
                'label' => 'Domaine'
            ])
            ->add('confidenceLevel', ChoiceType::class, [
                'choices' => [
                    'Pas fiable' => 4,
                    'Peu fiable' => 3,
                    'Assez fiable' => 2,
                    'Fiable' => 1,
                    'Très fiable' => 0,
                ],
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Niveau de confiance'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TargetSource::class,
        ]);
    }
}
