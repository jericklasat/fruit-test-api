<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\FruitService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/fruits')]
class FruitController extends AbstractController
{
  public function __construct(
    private FruitService $service,
  ){}

  #[Route('/paginated', methods: ['GET'])]
  public function getPaginated(Request $request): Response
  {
    $query = $request->query->all();

    $fruits = $this->service->getPaginated(
      (int) $query['draw'],
      (int) $query['start'],
      (int) $query['length'],
      $query['columns'][0]['search']['value'],
      $query['columns'][1]['search']['value'],
    );
    
    return $this->json($fruits);
  }

  #[Route('/favorite/paginated', methods: ['GET'])]
  public function getFavoritePaginated(Request $request): Response
  {
    $query = $request->query->all();
    $authorization = $request->headers->all()['authorization'] ?? '';
    $token = str_replace('Bearer ', '', $authorization)[0];

    $fruits = $this->service->getPaginatedFavoriteFruits(
      (int) $query['draw'],
      (int) $query['start'],
      (int) $query['length'],
      $query['columns'][0]['search']['value'],
      $query['columns'][1]['search']['value'],
      $token,
    );
    
    return $this->json($fruits);
  }
}