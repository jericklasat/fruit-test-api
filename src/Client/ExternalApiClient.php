<?php

declare(strict_types=1);

namespace App\Client;

use App\Model\Fruit;
use App\Model\Nutrition;
use Error;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class ExternalApiClient
{
  private ClientInterface $client;

  public function __construct()
  {
    $this->client = new Client();
  }

  /**
   * @return Fruit[]
   */
  public function getAllFruits(): array
  {
    $url = $_ENV['FRUITS_API_BASE_URL'] . '/fruit/all';

    try {
      $response = $this->client->request('GET', $url);
      $fruitsResponse = json_decode($response->getBody()->getContents(), true);
      $fruits = [];
  
      foreach($fruitsResponse as $fruit) {
        $nutrition = new Nutrition(
          $fruit['nutritions']['calories'] ?? 0,
          $fruit['nutritions']['fat'] ?? 0,
          $fruit['nutritions']['sugar'] ?? 0,
          $fruit['nutritions']['carbohydrates'] ?? 0,
          $fruit['nutritions']['protein'] ?? 0,
        );
  
        $fruit = new Fruit(
          $fruit['id'],
          $fruit['name'],
          $fruit['family'],
          $fruit['order'],
          $fruit['genus'],
          $nutrition,
        );
  
        $fruits[] = $fruit;
      }
  
      return $fruits;
    } catch(\Exception) {
      throw new Error('There is an error in fetching fruits from: ' . $url);
    }
  }

}