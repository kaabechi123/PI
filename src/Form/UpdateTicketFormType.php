<?php

namespace App\Form;

use App\Entity\Ticket;
use App\Entity\Event;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateTicketFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('event', EntityType::class, [
                'class' => Event::class,
                'choice_label' => 'name', // Display the event name
                'data' => $options['data']->getEvent(), // Pre-fill with the current ticket's event
            ])
            ->add('owner', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username', // Display the username of the owner
                'data' => $options['data']->getOwner(), // Pre-fill with the current ticket's owner
            ])
;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
