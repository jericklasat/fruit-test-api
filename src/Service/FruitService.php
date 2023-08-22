<?php

declare(strict_types=1);

namespace App\Service;

use App\Common\AppFormatter;
use App\Enum\Response as ResponseEnum;
use App\Repository\FruitRepository;
use App\Repository\UserFavoriteFruitRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class FruitService
{
  public function __construct(
    private AppFormatter $appFormatter,
    private FruitRepository $repository,
    private UserFavoriteFruitRepository $userFavoriteFruitRepository,
    private JWTTokenManagerInterface $jWTTokenManager,
  ){}

  public function getPaginated(
    int $draw,
    int $page,
    int $pageSize,
    string $nameSearchValue,
    string $familySearchValue,
  ): array {
      try {
        $fruits = $this->repository->paginated($draw, $page, $pageSize, $nameSearchValue, $familySearchValue);

        return $fruits;
      } catch (\Exception $exception) {
        return $this->appFormatter->formatResponse(ResponseEnum::FETCHING_FAILED->value, null, ['app' => $exception->getMessage()]);
      }
  }

  public function getPaginatedFavoriteFruits(
    int $draw,
    int $page,
    int $pageSize,
    string $nameSearchValue,
    string $familySearchValue,
    string $token
  ): array {
      try {
        $tokenPayload = $this->jWTTokenManager->parse($token);

        if (!isset($tokenPayload['roles']['userId'])) {
          return ['message' => 'User account is required.'];
        }

        $userId = $tokenPayload['roles']['userId'];

        $fruitsId = [];

        $favoriteFruits = $this->userFavoriteFruitRepository->findBy(['userId' => $userId]);

        foreach($favoriteFruits as $favoriteFruit) {
          $fruitsId[] = $favoriteFruit->getFruitId();
        }
        
        $fruits = $this->repository->paginatedByFruitsId(
          $draw,
          $page,
          $pageSize,
          $nameSearchValue,
          $familySearchValue,
          $fruitsId
        );

        return $fruits;
      } catch (\Exception $exception) {
        return $this->appFormatter->formatResponse(ResponseEnum::FETCHING_FAILED->value, null, ['app' => $exception->getMessage()]);
      }
  }
}