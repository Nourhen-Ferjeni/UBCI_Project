<?php

namespace App\Form;

use App\Entity\Absence;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AbsenceSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('startDate', DateType::class, [
            'widget' => 'single_text',
            'label' => 'Date de début',
            'required' => false,
        ])
        ->add('endDate', DateType::class, [
            'widget' => 'single_text',
            'label' => 'Date de fin',
            'required' => false,
        ])
        ->add('search', SubmitType::class, ['label' => 'Rechercher']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Absence::class,
        ]);
    }
}
