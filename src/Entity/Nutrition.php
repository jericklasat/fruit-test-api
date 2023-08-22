<?php

namespace App\Entity;

use App\Repository\NutritionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NutritionRepository::class)]
class Nutrition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $calories = null;

    #[ORM\Column(length: 255)]
    private ?float $fat = null;

    #[ORM\Column]
    private ?float $sugar = null;

    #[ORM\Column]
    private ?float $carbohydrates = null;

    #[ORM\Column]
    private ?float $protein = null;

    #[ORM\OneToOne(mappedBy: 'nutrition', cascade: ['persist', 'remove'])]
    private ?Fruit $fruit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCalories(): ?float
    {
        return $this->calories;
    }

    public function setCalories(float $calories): static
    {
        $this->calories = $calories;

        return $this;
    }

    public function getFat(): ?float
    {
        return $this->fat;
    }

    public function setFat(float $fat): static
    {
        $this->fat = $fat;

        return $this;
    }

    public function getSugar(): ?float
    {
        return $this->sugar;
    }

    public function setSugar(float $sugar): static
    {
        $this->sugar = $sugar;

        return $this;
    }

    public function getCarbohydrates(): ?float
    {
        return $this->carbohydrates;
    }

    public function setCarbohydrates(float $carbohydrates): static
    {
        $this->carbohydrates = $carbohydrates;

        return $this;
    }

    public function getProtein(): ?float
    {
        return $this->protein;
    }

    public function setProtein(float $protein): static
    {
        $this->protein = $protein;

        return $this;
    }

    public function getFruit(): ?Fruit
    {
        return $this->fruit;
    }

    public function setFruit(Fruit $fruit): static
    {
        // set the owning side of the relation if necessary
        if ($fruit->getNutrition() !== $this) {
            $fruit->setNutrition($this);
        }

        $this->fruit = $fruit;

        return $this;
    }
}
