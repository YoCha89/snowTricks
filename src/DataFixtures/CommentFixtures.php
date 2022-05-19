<?php

namespace App\DataFixtures;
use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\Account;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Provider\DateTime;

class CommentFixtures extends Fixture implements DependentFixtureInterface{

	public const COMMENT_REFERENCE = 'comment';

	public function load(ObjectManager $manager) {

		$faker = Factory::create('fr_FR');

		$str = 'azertyuiopqsdfghjklmwxcvbn0123456789';
		$num = '01234567890123456789';

		for ($count = 1; $count < 50; $count++) {

			$date = $faker->dateTime($format = 'Y-m-d H:i', $timezone = null);

			$schufS = str_shuffle($str);
			$schufS2 = str_shuffle($str);

			$commentA = new Comment();
			$commentB = new Comment();

			$content = $faker->paragraph($nbSentences = 2, $variableNbSentences = true);
			$content2 = $faker->paragraph($nbSentences = 2, $variableNbSentences = true);

			$trick = $this->getReference(TrickFixtures::TRICK_REFERENCE.'_'.$count);
			$trick2 = $this->getReference(TrickFixtures::TRICK_REFERENCE.'_'.$count);

			$account = $this->getReference(AccountFixtures::ACCOUNT_REFERENCE.'_'.$faker->numberBetween(0,6));
			$account2 = $this->getReference(AccountFixtures::ACCOUNT_REFERENCE.'_'.$faker->numberBetween(0,6));

			$commentA->setContent($content);
			$commentA->setDatePublish($date);
			$commentA->setTrick($trick);
			$commentA->setAccount($account);
			$commentA->setCreatedAt($date);
			$commentA->setLvl(1);

			$commentB->setContent($content2);
			$commentB->setDatePublish($date);
			$commentB->setTrick($trick);
			$commentB->setAccount($account);
			$commentB->setCreatedAt($date);
			$commentB->setLvl(1);


			$manager->persist($commentA);
			$manager->persist($commentB);

		}

		for ($count = 0; $count < 24; $count++) {

			$date = $faker->dateTime($format = 'Y-m-d H:i', $timezone = null);

			$schufS = str_shuffle($str);
			$schufS2 = str_shuffle($str);
			$schufS3 = str_shuffle($str);
			$schufS4 = str_shuffle($str);
			$schufS5 = str_shuffle($str);
			$schufS6 = str_shuffle($str);
			$schufS7 = str_shuffle($str);

			$comment = new Comment();
			$comment2 = new Comment();
			$comment3 = new Comment();
			$comment4 = new Comment();
			$comment5 = new Comment();
			$comment6 = new Comment();
			$comment7 = new Comment();

			$content = $faker->paragraph($nbSentences = 2, $variableNbSentences = true);
			$content2 = $faker->paragraph($nbSentences = 2, $variableNbSentences = true);
			$content3 = $faker->paragraph($nbSentences = 2, $variableNbSentences = true);
			$content4 = $faker->paragraph($nbSentences = 2, $variableNbSentences = true);			
			$content5 = $faker->paragraph($nbSentences = 2, $variableNbSentences = true);
			$content6 = $faker->paragraph($nbSentences = 2, $variableNbSentences = true);
			$content7 = $faker->paragraph($nbSentences = 2, $variableNbSentences = true);
        
			$trick = $this->getReference(TrickFixtures::TRICK_REFERENCE.'_'.$faker->numberBetween(0,49));
			$trick2 = $this->getReference(TrickFixtures::TRICK_REFERENCE.'_'.$faker->numberBetween(0,49));
			$trick3 = $this->getReference(TrickFixtures::TRICK_REFERENCE.'_'.$faker->numberBetween(0,49));
			$trick4 = $this->getReference(TrickFixtures::TRICK_REFERENCE.'_'.$faker->numberBetween(0,49));
			$trick5 = $this->getReference(TrickFixtures::TRICK_REFERENCE.'_'.$faker->numberBetween(0,49));
			$trick6 = $this->getReference(TrickFixtures::TRICK_REFERENCE.'_'.$faker->numberBetween(0,49));
			$trick7 = $this->getReference(TrickFixtures::TRICK_REFERENCE.'_'.$faker->numberBetween(0,49));

			$account = $this->getReference(AccountFixtures::ACCOUNT_REFERENCE.'_'.$faker->numberBetween(0,6));
			$account2 = $this->getReference(AccountFixtures::ACCOUNT_REFERENCE.'_'.$faker->numberBetween(0,6));
			$account3 = $this->getReference(AccountFixtures::ACCOUNT_REFERENCE.'_'.$faker->numberBetween(0,6));
			$account4 = $this->getReference(AccountFixtures::ACCOUNT_REFERENCE.'_'.$faker->numberBetween(0,6));
			$account5 = $this->getReference(AccountFixtures::ACCOUNT_REFERENCE.'_'.$faker->numberBetween(0,6));
			$account6 = $this->getReference(AccountFixtures::ACCOUNT_REFERENCE.'_'.$faker->numberBetween(0,6));
			$account7 = $this->getReference(AccountFixtures::ACCOUNT_REFERENCE.'_'.$faker->numberBetween(0,6));

			$comment->setContent($content);
			$comment->setDatePublish($date);
			$comment->setTrick($trick);
			$comment->setAccount($account);
			$comment->setCreatedAt($date);
			$comment->setLvl(3);

			$comment2->setContent($content2);
			$comment2->setDatePublish($date);
			$comment2->setTrick($trick);
			$comment2->setAccount($account);
			$comment2->setCreatedAt($date);
			$comment2->setLvl(3);


			$comment3->setContent($content);
			$comment3->setDatePublish($date);
			$comment3->setTrick($trick);
			$comment3->setAccount($account);
			$comment3->setCreatedAt($date);
			$comment3->addComment($comment);
			$comment3->addComment($comment2);
			$comment3->setLvl(2);


			$comment4->setContent($content);
			$comment4->setDatePublish($date);
			$comment4->setTrick($trick);
			$comment4->setAccount($account);
			$comment4->setCreatedAt($date);
			$comment4->addComment($comment3);
			$comment4->setLvl(1);


			$comment5->setContent($content);
			$comment5->setDatePublish($date);
			$comment5->setTrick($trick);
			$comment5->setAccount($account);
			$comment5->setCreatedAt($date);
			$comment5->setLvl(5);


			$comment6->setContent($content);
			$comment6->setDatePublish($date);
			$comment6->setTrick($trick);
			$comment6->setAccount($account);
			$comment6->setCreatedAt($date);
			$comment6->setLvl(5);


			$comment7->setContent($content);
			$comment7->setDatePublish($date);
			$comment7->setTrick($trick);
			$comment7->setAccount($account);
			$comment7->setCreatedAt($date);
			$comment7->addComment($comment5);
			$comment7->addComment($comment6);
			$comment7->setLvl(4);


			$comment2->addComment($comment7);

			$manager->persist($comment);
			$manager->persist($comment4);
			$manager->persist($comment2);
			$manager->persist($comment3);
			$manager->persist($comment5);
			$manager->persist($comment6);
			$manager->persist($comment7);

		}

		$manager->flush();
		
	}

	public function getDependencies() {
        return [
            AccountFixtures::class,
            TrickFixtures::class,
        ];
    }
}