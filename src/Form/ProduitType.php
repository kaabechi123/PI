<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\TypeMatiereEnum;  // Import de l'énumération
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('quantite')
            ->add('unite')
            // Ajout du champ typeMatière avec les objets de l'énumération
            ->add('typeMatiere', ChoiceType::class, [
                'choices' => [
                    'Plastique' => TypeMatiereEnum::PLASTIQUE,
                    'Verre' => TypeMatiereEnum::VERRE,
                    'Bois' => TypeMatiereEnum::BOIS,
                    'Textile' => TypeMatiereEnum::TEXTILE,
                ],
                'expanded' => false,  // Choix sous forme de liste déroulante
                'multiple' => false,  // Un seul choix possible
                'choice_label' => function(TypeMatiereEnum $enum) {
                    return ucfirst($enum->value);  // Utilise la valeur de l'énumération avec la première lettre en majuscule
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
