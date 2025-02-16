<?php

namespace App\Form;

use App\Entity\Livraison;
use App\Entity\SocieteRecyclage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;    // Import DateType
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('societeRecyclage', EntityType::class, [
                'class' => SocieteRecyclage::class,
                'choice_label' => 'nom',
                'placeholder' => 'Sélectionnez une société',
                'required' => true,  // Assure que ce champ est bien requis
            ])
            ->add('poids', NumberType::class)
            ->add('produit', TextType::class);  // Assure-toi d'utiliser le bon nom du champ
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livraison::class,
        ]);
    }
}
