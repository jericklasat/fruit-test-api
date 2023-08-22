<?php

namespace App\Repository;

use App\Entity\UserFavoriteFruit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserFavoriteFruit>
 *
 * @method UserFavoriteFruit|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserFavoriteFruit|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserFavoriteFruit[]    findAll()
 * @method UserFavoriteFruit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserFavoriteFruitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFavoriteFruit::class);
    }

    public function create(
        int $userId,
        int $fruitId,
    ): ?int {
        if ($this->isExisting($userId, $fruitId)) {
            return null;
        }

        $entity = new UserFavoriteFruit();
        $entity->setUserId($userId);
        $entity->setFruitId($fruitId);

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        return $entity->getId();
    }

    public function remove(
        int $userId,
        int $fruitId,
    ): bool {
        $entity = $this->findOneBy(['userId' => $userId, 'fruitId' => $fruitId]);

        if (null == $entity) {
            return false;
        }

        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();

        return true;
    }

    public function isAddedToFavoriteMaximum(int $userId): bool
    {
        $maximumAllowed = 10;
        $result = $this->findBy(['userId' => $userId]);

        return \count($result) === $maximumAllowed;
    }

    private function isExisting(int $userId, int $fruitId): bool
    {
        $user = $this->findOneBy([
            'userId' => $userId,
            'fruitId' => $fruitId
        ]);

        return $user != null;
    }
}
