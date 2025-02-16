<?php

namespace App\Entity;

use App\Repository\LivraisonRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivraisonRepository::class)]
class Livraison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?float $poids = null;

    #[ORM\ManyToOne(targetEntity: SocieteRecyclage::class, inversedBy: 'livraisons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SocieteRecyclage $societeRecyclage = null;

    #[ORM\Column(length: 255)]
    private ?string $produit = null; // Nouvel attribut

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getPoids(): ?float
    {
        return $this->poids;
    }

    public function setPoids(float $poids): static
    {
        $this->poids = $poids;

        return $this;
    }

    public function getSocieteRecyclage(): ?SocieteRecyclage
    {
        return $this->societeRecyclage;
    }

    public function setSocieteRecyclage(?SocieteRecyclage $societeRecyclage): static
    {
        $this->societeRecyclage = $societeRecyclage;

        return $this;
    }

    public function getProduit(): ?string
    {
        return $this->produit;
    }

    public function setProduit(string $produit): static
    {
        $this->produit = $produit;

        return $this;
    }
}