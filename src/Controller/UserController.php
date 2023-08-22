<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/user')]
class UserController extends AbstractController
{
  public function __construct(
    private UserService $service,
  ){}

  #[Route('/create', methods: ['POST'])]
  public function create(Request $request): Response
  {
    $data = json_decode($request->getContent(), true);

    return $this->json(
      $this->service->create($data['emailAddress'] ?? '', $data['password'] ?? '')
    );
  }

  #[Route('/login', name: 'login2',  methods: ['POST'])]
  public function login(Request $request): Response
  {
    $data = json_decode($request->getContent(), true);

    return $this->json(
      $this->service->login($data['emailAddress'] ?? '', $data['password'] ?? '')
    );
  }

  #[Route('/favorite/fruit/add', methods: ['GET'])]
  public function addFavorite(Request $request): Response
  {
    $fruitId = (int) $request->query->get('fruitId');
    $authorization = $request->headers->all()['authorization'] ?? '';
    $token = str_replace('Bearer ', '', $authorization)[0];

    return $this->json(
      $this->service->createFavoriteFruit($token, $fruitId)
    );
  }

  #[Route('/favorite/fruit/remove', methods: ['GET'])]
  public function removeFavorite(Request $request): Response
  {
    $fruitId = (int) $request->query->get('fruitId');
    $authorization = $request->headers->all()['authorization'] ?? '';
    $token = str_replace('Bearer ', '', $authorization)[0];

    return $this->json(
      $this->service->removeFavoriteFruit($token, $fruitId)
    );
  }
}