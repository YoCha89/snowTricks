<?php

namespace App\DataFixtures;
use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Faker\Provider\DateTime;

class TrickFixtures extends Fixture {

	public const TRICK_REFERENCE = 'trick';

	public function load(ObjectManager $manager) {

		$faker = Factory::create('fr_FR');

		$str = 'azertyuiopqsdfghjklmwxcvbn0123456789';
		$num = '01234567890123456789';


		for ($count = 0; $count < 50; $count++) {

			$schufS = str_shuffle($str);
			$schufS2 = str_shuffle($str);
			$schufI = str_shuffle($num);

			$date = $faker->dateTime($format = 'Y-m-d H:i', $timezone = null);

			$name1 = substr($schufS, 0, 5);
			$name2 = substr($schufS2, 0, 5);
			$name = $name1.' '.$name2;
			$content = $faker->paragraph($nbSentences = 3, $variableNbSentences = true);
			$slug = '/'.$name1.'_'.$name2;

			$trick = new Trick();

			$trick->setName($name);
			$trick->setContent($content);
			$trick->setSlug($slug);
			$trick->setCreatedAt($date);

			$manager->persist($trick);
			$manager->flush();
		}

		$this->addReference(self::TRICK_REFERENCE, $trick);
	}
}