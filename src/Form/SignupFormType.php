<?php

namespace App\Form;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class SignupFormType extends AbstractType
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Client' => 'ROLE_CLIENT',
                    'Organisation' => 'ROLE_ORGANISATION',
                    'Delivery Driver' => 'ROLE_DELIVERY',
                ],
            ]);

        // Log form data before it is bound to the entity
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            $this->logger->debug('SignupFormType: Pre-set data.', [
                'form_data' => $data,
            ]);
        });

        // Log form data after it is submitted but before it is validated
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            $this->logger->debug('SignupFormType: Pre-submit data.', [
                'raw_data' => $data,
            ]);
        });

        // Log form data after it is submitted and bound to the entity
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $formData = $form->getData();
        
            // Output form data
        
            // Output validation errors
            if (!$form->isValid()) {
                $errors = [];
                foreach ($form->getErrors(true, true) as $error) {
                    $errors[] = [
                        'field' => $error->getOrigin()->getName(),
                        'message' => $error->getMessage(),
                    ];
                }
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}