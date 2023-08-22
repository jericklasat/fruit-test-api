<?php

namespace App\Entity;

use App\Repository\UserFavoriteFruitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserFavoriteFruitRepository::class)]
class UserFavoriteFruit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $userId = null;

    #[ORM\Column]
    private ?int $fruitId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getFruitId(): ?int
    {
        return $this->fruitId;
    }

    public function setFruitId(int $fruitId): static
    {
        $this->fruitId = $fruitId;

        return $this;
    }
}
