<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Fruit
{
  public function __construct(
    private int $id,
    private string $name,
    private string $family,
    private string $orderName,
    private string $genus,
    private Nutrition $nutrition,
  ){}
  
  /**
   * @Assert\NotBlank
   * @Assert\GreaterThan(0)
   * @return int
   */
  public function getId(): int
  {
      return $this->id;
  }

  /**
   * @Assert\NotBlank
   * @return string
   */
  public function getName(): string
  {
      return $this->name;
  }

  /**
   * @Assert\NotBlank
   * @return string
   */
  public function getFamily(): string
  {
      return $this->family;
  }

  /**
   * @Assert\NotBlank
   * @return string
   */
  public function getOrderName(): string
  {
      return $this->orderName;
  }

  /**
   * @Assert\NotBlank
   * @return string
   */
  public function getGenus(): string
  {
      return $this->genus;
  }

  public function getNutrition(): Nutrition
  {
    return $this->nutrition;
  }
}