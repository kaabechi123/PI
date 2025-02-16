<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use App\Entity\Event;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddEventFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {$builder
        ->add('name')
        ->add('description')
        ->add('startDate', DateTimeType::class, [
            'widget' => 'single_text',
            'data' => new \DateTime(), // Set default value to current date and time
        ])
        ->add('endDate', DateTimeType::class, [
            'widget' => 'single_text',
            'data' => new \DateTime(), // Set default value to current date and time
        ])
        ->add('location')
        ->add('organizer', EntityType::class, [
            'class' => User::class,
            'choice_label' => 'id',
            'disabled' => true,  // Disabled as the organizer is set automatically
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
