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

		$str = 'azertyuiopqsdfghjklmwxcvbn';

		for ($count = 0; $count < 50; $count++) {

			$schufS = str_shuffle($str);

			$date = $faker->dateTime($format = 'Y-m-d H:i', $timezone = null);

			$name = ucfirst(substr($schufS, 0, 5)).'_'.ucfirst(substr($schufS, 6, 4));
			$content = $faker->paragraph($nbSentences = 2, $variableNbSentences = true);
			$slug = $name .'_trick';

			$trick = new Trick();

			$trick->setName($name);
			$trick->setContent($content);
			$trick->setSlug($slug);
			$trick->setCreatedAt($date);

			$this->setReference(self::TRICK_REFERENCE.'_'.$count, $trick);

			$manager->persist($trick);
			$manager->flush();
		}

	}
}