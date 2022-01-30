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


        $admin = new Account;

        $passA = $this->encoder->encodePassword($admin, 'password');
        $admEmail = 'admin@gmail.com';

        $admin->setFullName('Admin')
            ->setPassword($passA)
            ->setEmail($admEmail)
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        for ($a=0; $a<30; $a++){
            $account = new Account();

            $pass = $this->encoder->encodePassword($account, 'password');

            $account->setEmail('Acc'.$a.'@gmail.command')
            ->setFullName($faker->name())
            ->setPassword($pass);

            $manager->persist($account);
        }

        $manager->flush();

        $this->addReference(self::ACCOUNT_REFERENCE, $account);
    }
}
