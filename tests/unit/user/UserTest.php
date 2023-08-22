<?php

declare(strict_types=1);


namespace App\Tests\Unit;

use App\Entity\UserAccount;
use App\Repository\UserAccountRepository;
use App\Repository\UserFavoriteFruitRepository;
use App\Service\UserService;
use App\Tests\UnitTester;
use DateTimeImmutable;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserTest extends \Codeception\Test\Unit
{
    use ProphecyTrait;

    protected UnitTester $tester;

    private UserService $subject;

    private UserPasswordHasherInterface|ObjectProphecy $userPasswordHasher;
    private UserAccountRepository|ObjectProphecy $userAccountRepository;
    private UserFavoriteFruitRepository|ObjectProphecy $userFavoriteFruitRepository;
    private JWTTokenManagerInterface|ObjectProphecy  $jwtTokenManager;


    public function setUp(): void
    {
        parent::setUp();

        $this->userPasswordHasher = $this->prophesize(UserPasswordHasherInterface::class);
        $this->userAccountRepository = $this->prophesize(UserAccountRepository::class);
        $this->userFavoriteFruitRepository = $this->prophesize(UserFavoriteFruitRepository::class);
        $this->jwtTokenManager = $this->prophesize(JWTTokenManagerInterface::class);
    }

    public function testLoginSuccess(): void
    {
        $hashedPassword = '$2y$13$SupHyM2RNKxeBAnPrzz/huhG6QiqOGBLwL5U937qRl4Lhw31Yy1/G';
        $email = 'icamina@mail.com';
        $password = 'pass1234';
        $currentDateTime = new DateTimeImmutable();
        $currentDateTime->format("Y-m-d H:m:s");

        $userAccount = new UserAccount();
        $userAccount->setId(1);
        $userAccount->setEmailAddress($email);
        $userAccount->setPassword($hashedPassword);
        $userAccount->setCreatedAt($currentDateTime);

        $this->userAccountRepository
            ->findByEmailAddress($email)
            ->willReturn($userAccount);

        $this->userPasswordHasher
            ->isPasswordValid($userAccount, $password)
            ->willReturn(true);

        $this->jwtTokenManager
            ->create($userAccount)
            ->willReturn('token');

        $subject = new UserService(
            $this->userPasswordHasher->reveal(),
            $this->userAccountRepository->reveal(),
            $this->userFavoriteFruitRepository->reveal(),
            $this->jwtTokenManager->reveal(),
        );

        $result = $subject->login(
            $email,
            $password,
        );

        $this->assertEquals($result['token'], 'token');
    }
}
