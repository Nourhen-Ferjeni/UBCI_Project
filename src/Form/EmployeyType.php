<?php

namespace App\Form;

use App\Entity\Employer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, ['label' => 'Nom'])
        ->add('prenom', TextType::class, ['label' => 'Prénom'])
        ->add('email', EmailType::class, ['label' => 'Email'])
        ->add('Tel', IntegerType::class, ['label' => 'Téléphone'])
        ->add('poste', TextType::class, ['label' => 'Poste'])
        ->add('cin', TextType::class, ['label' => 'Carte Cin'])
        ->add('hiredate')
        ->add('salary', TextType::class, ['label' => 'hiredate'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employer::class,
        ]);
    }
}
