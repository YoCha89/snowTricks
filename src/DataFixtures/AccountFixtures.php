<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Account;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountFixtures extends Fixture
{
    public const ACCOUNT_REFERENCE = 'account';

    protected $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($a=0; $a<7; $a++){
            $account = new Account();

            $pass = $this->encoder->encodePassword($account, 'password');

            $account->setEmail('Acc'.$a.'@gmail.command')
            ->setFullName($faker->name())
            ->setPassword($pass);

            $this->setReference(self::ACCOUNT_REFERENCE.'_'.$a, $account);

            $manager->persist($account);
        }

        $manager->flush();

    }
}
