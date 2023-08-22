<?php

namespace App\Model;

class Nutrition
{
  public function __construct(
    private float $calories,
    private float $fat,
    private float $sugar,
    private float $carbohydrates,
    private float $protein,
  ){}
  
  /**
   * @Assert\NotBlank
   * @return float
   */
  public function getCalories(): float
  {
      return $this->calories;
  }
  
  /**
   * @Assert\NotBlank
   * @return float
   */
  public function getFat(): float
  {
      return $this->fat;
  }
  
  /**
   * @Assert\NotBlank
   * @return float
   */
  public function getSugar(): float
  {
      return $this->sugar;
  }
  
  /**
   * @Assert\NotBlank
   * @return float
   */
  public function getCarbohydrates(): float
  {
      return $this->carbohydrates;
  }
  
  /**
   * @Assert\NotBlank
   * @return float
   */
  public function getProtein(): float
  {
      return $this->protein;
  }
}