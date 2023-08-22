<?php

declare(strict_types=1);

namespace App\Service;

use App\Common\AppFormatter;
use App\Repository\UserAccountRepository;
use App\Repository\UserFavoriteFruitRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class UserService
{
  public function __construct(
    private UserPasswordHasherInterface $userPasswordHasher,
    private UserAccountRepository $repository,
    private UserFavoriteFruitRepository $userFavoriteFruitRepository,
    private JWTTokenManagerInterface $jWTTokenManager,
  ) {
  }

  public function create(string $emailAddress, string $password): bool
  {
    $response = $this->repository->create($emailAddress, $password);

    return null != $response;
  }

  public function login(string $emailAddress, string $password): array
  {
    $user = $this->repository->findByEmailAddress($emailAddress);

    if (null == $user) {
      return ['message' => 'User account not found for email: ' . $emailAddress];
    }

    $isPasswordValid = $this->userPasswordHasher->isPasswordValid($user, $password);

    if (!$isPasswordValid) {
        return ['message' => 'Invalid Password'];
    }

    return [
      'token' => $this->jWTTokenManager->create($user),
    ];
  }

  public function createFavoriteFruit(string $token, int $fruitId): array
  {
    $tokenPayload = $this->jWTTokenManager->parse($token);

    if (!isset($tokenPayload['roles']['userId'])) {
      return ['message' => 'User account is required.'];
    }

    $userId = $tokenPayload['roles']['userId'];

    $isFavoriteAtMaximum = $this->userFavoriteFruitRepository->isAddedToFavoriteMaximum($userId);

    if ($isFavoriteAtMaximum) {
      return ['message' => 'Favorite fruit already reached its limit.'];
    }
    
    $id = $this->userFavoriteFruitRepository->create($userId, $fruitId);

    if (null == $id) {
      return ['message' => 'Fruit is already added to favorite for this user.'];
    }

    return ['message' => 'Fruit is successfully added to favorite.'];;
  }

  public function removeFavoriteFruit(string $token, int $fruitId): array
  {
    $tokenPayload = $this->jWTTokenManager->parse($token);

    if (!isset($tokenPayload['roles']['userId'])) {
      return ['message' => 'User account is required.'];
    }

    $userId = $tokenPayload['roles']['userId'];
    
    $isRemoved = $this->userFavoriteFruitRepository->remove($userId, $fruitId);

    if (! $isRemoved) {
      return ['message' => 'Cannot remove fruit from favorite lists.'];
    }

    return ['message' => 'Favorite fruit is successfully removed.'];;
  }
}