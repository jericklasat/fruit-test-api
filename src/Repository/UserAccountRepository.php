<?php

namespace App\Repository;

use App\Entity\UserAccount;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @extends ServiceEntityRepository<UserAccount>
 *
 * @method UserAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserAccount[]    findAll()
 * @method UserAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserAccountRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private UserPasswordHasherInterface $userPasswordHasher,
    ){
        parent::__construct($registry, UserAccount::class);
    }

    public function create(
        string $emailAddress,
        string $password,
    ): ?int {
        if ($this->isExisting($emailAddress)) {
            return null;
        }

        $currentDateTime = new DateTimeImmutable();
        $currentDateTime->format("Y-m-d H:m:s");

        $user = new UserAccount();
        $hashedPassword = $this->userPasswordHasher->hashPassword($user, $password);
        $user->setEmailAddress($emailAddress);
        $user->setPassword($hashedPassword);
        $user->setCreatedAt($currentDateTime);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        return $user->getId();
    }

    public function findByEmailAddress(string $emailAddress): ?UserAccount
    {
        return $this->findOneBy([
            'emailAddress' => $emailAddress,
            'deletedAt' => null
        ]);
    }

    public function isExisting(string $emailAddress): bool
    {
        $user = $this->findOneBy([
            'emailAddress' => $emailAddress,
            'deletedAt' => null
        ]);

        return $user != null;
    }
}
