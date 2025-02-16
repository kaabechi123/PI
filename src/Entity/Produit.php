<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use App\Entity\TypeMatiereEnum;  // Import de l'énumération
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du produit est obligatoire.")]
    private ?string $nom = null;

    #[ORM\Column(type: 'float')]
    #[Assert\NotBlank(message: "La quantité du produit est obligatoire.")]
    private ?float $quantite = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'unité du produit est obligatoire.")]
    private ?string $unite = null;

    // Ajoutez l'attribut typeMatière
    #[ORM\Column(type: "string", enumType: TypeMatiereEnum::class)]
    #[Assert\NotBlank(message: "Le type matière du produit est obligatoire.")]
    private TypeMatiereEnum $typeMatiere;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getQuantite(): ?float
    {
        return $this->quantite;
    }
    
    public function setQuantite(float $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }


    public function getUnite(): ?string
    {
        return $this->unite;
    }

    public function setUnite(string $unite): static
    {
        $this->unite = $unite;

        return $this;
    }

    // Getter et setter pour typeMatière
    public function getTypeMatiere(): ?TypeMatiereEnum
    {
        return $this->typeMatiere instanceof TypeMatiereEnum ? $this->typeMatiere : TypeMatiereEnum::tryFrom($this->typeMatiere);
    }


    public function setTypeMatiere(TypeMatiereEnum|string $typeMatiere): self
    {
        // Convertit une chaîne en Enum si nécessaire
        $this->typeMatiere = is_string($typeMatiere) ? TypeMatiereEnum::tryFrom($typeMatiere) : $typeMatiere;
        return $this;
    }

}
