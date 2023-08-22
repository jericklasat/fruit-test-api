<?php

declare(strict_types=1);

namespace App\Repository;

use App\Common\RepositoryHelper;
use App\Entity\Fruit;
use App\Entity\Nutrition;
use App\Model\Fruit as FruitModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fruit>
 *
 * @method Fruit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fruit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fruit[]    findAll()
 * @method Fruit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FruitRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private RepositoryHelper $helper,
    ) {
        parent::__construct($registry, Fruit::class);
    }

    /**
     * @param FruitModel[] $fruits
     */
    public function bulkCreate(array $fruits): void {
        foreach($fruits as $fruit) {
            $nutrition = $fruit->getNutrition();

            $nutritionEntity = new Nutrition();
            $nutritionEntity->setCalories($nutrition->getCalories());
            $nutritionEntity->setFat($nutrition->getFat());
            $nutritionEntity->setSugar($nutrition->getSugar());
            $nutritionEntity->setCarbohydrates($nutrition->getCarbohydrates());
            $nutritionEntity->setProtein($nutrition->getProtein());

            $fruitEntity = new Fruit();
            $fruitEntity->setId($fruit->getId());
            $fruitEntity->setName($fruit->getName());
            $fruitEntity->setFamily($fruit->getFamily());
            $fruitEntity->setOrderName($fruit->getOrderName());
            $fruitEntity->setGenus($fruit->getGenus());
            $fruitEntity->setNutrition($nutritionEntity);

            $this->_em->persist($fruitEntity);
        }

        $this->_em->flush();
    }

    public function cleanup(): void
    {
        $connection = $this->_em->getConnection();
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS=0');
        $connection->executeQuery('TRUNCATE TABLE fruit;');
        $connection->executeQuery('TRUNCATE TABLE nutrition;');
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS=1');
        $this->_em->flush();
    }

    /**
     * @param int $draw
     * @param int $page
     * @param int $pageSize
     * @param string $nameSearchValue
     * @param string $familySearchValue
     * @return array<string, mixed>|null
     */
    public function paginated(
        int $draw,
        int $page,
        int $pageSize,
        string $nameSearchValue,
        string $familySearchValue,
    ): ?array {
        $result = [];

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT f.name, f.family, f.order_name, f.genus, n.calories, n.fat,
                n.sugar, n.carbohydrates, n.protein, f.id as fruit_id
                FROM fruit as f
                LEFT JOIN nutrition as n ON n.id = f.nutrition_id ";
        
        if (strlen($nameSearchValue) > 0 && strlen($familySearchValue) == 0) {
            $sql .= "WHERE f.name LIKE '%$nameSearchValue%' ";
        }

        if (strlen($familySearchValue) > 0 && strlen($nameSearchValue) == 0) {
            $sql .= "WHERE f.family LIKE '%$familySearchValue%' ";
        }

        if (strlen($nameSearchValue) > 0 && strlen($familySearchValue) > 0) {
            $sql .= "WHERE f.name LIKE '%$nameSearchValue%' AND f.family LIKE '%$familySearchValue%' ";
        }

        $result['draw'] = $draw;

        $total = $this->helper->getCustomQueryPaginatedTotalItems($conn, $sql);

        $sql .= "LIMIT $pageSize OFFSET $page";

        $stmt = $conn->prepare($sql);
        $query = $stmt->executeQuery();
        $data = $query->fetchAllAssociative();
        $result['data'] = $data;
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] = $total;

        return $result;
    }

    /**
     * @param int $draw
     * @param int $page
     * @param int $pageSize
     * @param string $nameSearchValue
     * @param string $familySearchValue
     * @param int[] $fruitIds
     * @return array<string, mixed>|null
     */
    public function paginatedByFruitsId(
        int $draw,
        int $page,
        int $pageSize,
        string $nameSearchValue,
        string $familySearchValue,
        array $fruitIds,
    ): ?array {
        $result = [];
        $fruitIdsParam = implode(',', $fruitIds);

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT f.name, f.family, f.order_name, f.genus, n.calories, n.fat,
                n.sugar, n.carbohydrates, n.protein, f.id as fruit_id
                FROM fruit as f
                LEFT JOIN nutrition as n ON n.id = f.nutrition_id
                WHERE f.id IN ($fruitIdsParam) ";

        if (strlen($nameSearchValue) > 0 && strlen($familySearchValue) == 0) {
            $sql .= "AND f.name LIKE '%$nameSearchValue%' ";
        }

        if (strlen($familySearchValue) > 0 && strlen($nameSearchValue) == 0) {
            $sql .= "AND f.family LIKE '%$familySearchValue%' ";
        }

        if (strlen($nameSearchValue) > 0 && strlen($familySearchValue) > 0) {
            $sql .= "AND f.name LIKE '%$nameSearchValue%' AND f.family LIKE '%$familySearchValue%' ";
        }

        $result['draw'] = $draw;

        $total = $this->helper->getCustomQueryPaginatedTotalItems($conn, $sql);

        $sql .= "LIMIT $pageSize OFFSET $page";

        $query = $conn->executeQuery(
            $sql,
            // ['fruitsId' => $fruitIds],
            // ['fruitsId' => ArrayParameterType::INTEGER]
        );

        $data = $query->fetchAllAssociative();
        
        $result['data'] = $data;
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] = $total;

        return $result;
    }
}
