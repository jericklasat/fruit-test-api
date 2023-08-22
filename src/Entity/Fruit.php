<?php

namespace App\Entity;

use App\Repository\FruitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FruitRepository::class)]
class Fruit
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $family = null;

    #[ORM\Column(length: 255)]
    private ?string $orderName = null;

    #[ORM\Column(length: 255)]
    private ?string $genus = null;

    #[ORM\OneToOne(inversedBy: 'fruit', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Nutrition $nutrition = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFamily(): ?string
    {
        return $this->family;
    }

    public function setFamily(string $family): static
    {
        $this->family = $family;

        return $this;
    }

    public function getOrderName(): ?string
    {
        return $this->orderName;
    }

    public function setOrderName(string $orderName): static
    {
        $this->orderName = $orderName;

        return $this;
    }

    public function getGenus(): ?string
    {
        return $this->genus;
    }

    public function setGenus(string $genus): static
    {
        $this->genus = $genus;

        return $this;
    }

    public function getNutrition(): ?Nutrition
    {
        return $this->nutrition;
    }

    public function setNutrition(Nutrition $nutrition): static
    {
        $this->nutrition = $nutrition;

        return $this;
    }
}
