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

		for ($count = 0; $count < 10; $count++) {

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

/*			$this->addReference(self::COMMENT_REFERENCE, $comment);
			$this->addReference(self::COMMENT_REFERENCE, $comment2);
			$this->addReference(self::COMMENT_REFERENCE, $comment3);
			$this->addReference(self::COMMENT_REFERENCE, $comment4);
			$this->addReference(self::COMMENT_REFERENCE, $comment5);
			$this->addReference(self::COMMENT_REFERENCE, $comment6);
			$this->addReference(self::COMMENT_REFERENCE, $comment7);*/


			$author = substr($schufS, 0, 6);
			$author2 = substr($schufS2, 0, 6);			
			$author3 = substr($schufS3, 0, 6);
			$author4 = substr($schufS4, 0, 6);
			$author5 = substr($schufS5, 0, 6);			
			$author6 = substr($schufS6, 0, 6);
			$author7 = substr($schufS7, 0, 6);

			$content = $faker->paragraph($nbSentences = 3, $variableNbSentences = true);
			$content2 = $faker->paragraph($nbSentences = 3, $variableNbSentences = true);
			$content3 = $faker->paragraph($nbSentences = 3, $variableNbSentences = true);
			$content4 = $faker->paragraph($nbSentences = 3, $variableNbSentences = true);			
			$content5 = $faker->paragraph($nbSentences = 3, $variableNbSentences = true);
			$content6 = $faker->paragraph($nbSentences = 3, $variableNbSentences = true);
			$content7 = $faker->paragraph($nbSentences = 3, $variableNbSentences = true);

			$validated = null;

			$trick = $this->getReference(TrickFixtures::TRICK_REFERENCE);
			$trick2 = $this->getReference(TrickFixtures::TRICK_REFERENCE);
			$trick3 = $this->getReference(TrickFixtures::TRICK_REFERENCE);
			$trick4 = $this->getReference(TrickFixtures::TRICK_REFERENCE);
			$trick5 = $this->getReference(TrickFixtures::TRICK_REFERENCE);
			$trick6 = $this->getReference(TrickFixtures::TRICK_REFERENCE);
			$trick7 = $this->getReference(TrickFixtures::TRICK_REFERENCE);

			$account = $this->getReference(AccountFixtures::ACCOUNT_REFERENCE);
			$account2 = $this->getReference(AccountFixtures::ACCOUNT_REFERENCE);
			$account3 = $this->getReference(AccountFixtures::ACCOUNT_REFERENCE);
			$account4 = $this->getReference(AccountFixtures::ACCOUNT_REFERENCE);
			$account5 = $this->getReference(AccountFixtures::ACCOUNT_REFERENCE);
			$account6 = $this->getReference(AccountFixtures::ACCOUNT_REFERENCE);
			$account7 = $this->getReference(AccountFixtures::ACCOUNT_REFERENCE);

			$comment->setAuthor($author);
			$comment->setContent($content);
			$comment->setDatePublish($date);
			$comment->setValidated($validated);
			$comment->setTrick($trick);
			$comment->setAccount($account);
			$comment->setCreatedAt($date);


			$comment2->setAuthor($author2);
			$comment2->setContent($content2);
			$comment2->setDatePublish($date);
			$comment2->setValidated($validated);
			$comment2->setTrick($trick);
			$comment2->setAccount($account);
			$comment2->setCreatedAt($date);


			$comment3->setAuthor($author);
			$comment3->setContent($content);
			$comment3->setDatePublish($date);
			$comment3->setValidated($validated);
			$comment3->setTrick($trick);
			$comment3->setAccount($account);
			$comment3->setCreatedAt($date);
			$comment3->addComment($comment);
			$comment3->addComment($comment2);


			$comment4->setAuthor($author);
			$comment4->setContent($content);
			$comment4->setDatePublish($date);
			$comment4->setValidated($validated);
			$comment4->setTrick($trick);
			$comment4->setAccount($account);
			$comment4->setCreatedAt($date);
			$comment4->addComment($comment3);


			$comment5->setAuthor($author);
			$comment5->setContent($content);
			$comment5->setDatePublish($date);
			$comment5->setValidated($validated);
			$comment5->setTrick($trick);
			$comment5->setAccount($account);
			$comment5->setCreatedAt($date);


			$comment6->setAuthor($author);
			$comment6->setContent($content);
			$comment6->setDatePublish($date);
			$comment6->setValidated($validated);
			$comment6->setTrick($trick);
			$comment6->setAccount($account);
			$comment6->setCreatedAt($date);


			$comment7->setAuthor($author);
			$comment7->setContent($content);
			$comment7->setDatePublish($date);
			$comment7->setValidated($validated);
			$comment7->setTrick($trick);
			$comment7->setAccount($account);
			$comment7->setCreatedAt($date);
			$comment3->addComment($comment5);
			$comment3->addComment($comment6);

			$manager->persist($comment);
			$manager->persist($comment4);
			$manager->persist($comment2);
			$manager->persist($comment3);
			$manager->persist($comment5);
			$manager->persist($comment6);
			$manager->persist($comment7);

			$manager->flush();
		}

		
	}

	public function getDependencies() {
        return [
            AccountFixtures::class,
            TrickFixtures::class,
        ];
    }
}
/*
 $tricks = $em->getRepository(Trick::class)->findAll();
        $accounts = $em->getRepository(Account::class)->findAll();

        foreach($tricks as $t){
            for($i=0; $i<=5; $i++){
                $ind = array_rand($accounts, 1);
                $account = $accounts[$ind];

                $thread = new thread();

                $tmp = new comment();
                $tmp->setThread($thread);
                $tmp->setTrick($t);
                $tmp->setLvl(1);
                $tmp->setCreatedAt(new \DateTime());
                $tmp->setAccount($account);
                $tmp->setContent('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur feugiat quam a vehicula efficitur. Morbi mattis pretium consectetur. In sed lacinia leo. Pellentesque lobortis placerat risus, quis pretium turpis sollicitudin non.');
                if($i == 2 || $i == 4){
                    for($i=0; $i<=5; $i++){
                        $ind = array_rand($accounts, 1);
                        $account = $accounts[$ind];

                        $tmp2 = new comment();
                        $tmp2->setTrick($t);
                        $tmp2->setLvl(2);
                        $tmp2->setThread($thread);
                        $tmp2->setCommentParent($tmp);
                        $tmp2->setCreatedAt(new \DateTime());
                        $tmp2->setAccount($account);
                        $tmp2->setContent('Morbi mattis pretium consectetur. In sed lacinia leo. Pellentesque lobortis placerat risus, quis pretium turpis sollicitudin non. Mauris id lacinia nibh, non feugiat nunc. Nam sit amet metus mollis, ullamcorper augue vitae, tristique ipsum. Nunc scelerisque eros at interdum dignissim. Fusce pellentesque tellus accumsan est egestas, id blandit dui ultricies. Sed lacinia enim felis, quis hendrerit felis semper a.');
                        if($i == 3 || $i == 7){
                            $ind = array_rand($accounts, 1);
                            $account = $accounts[$ind];

                            $tmp3 = new comment();
                            $tmp3->setTrick($t);
                            $tmp3->setLvl(3);
                            $tmp3->setThread($thread);
                            $tmp3->setCommentParent($tmp2);
                            $tmp3->setCreatedAt(new \DateTime());
                            $tmp3->setAccount($account);
                            $tmp3->setContent('Nunc scelerisque eros at interdum dignissim. Fusce pellentesque tellus accumsan est egestas, id blandit dui ultricies. Sed lacinia enim felis, quis hendrerit felis semper a. Curabitur ultricies, sapien id semper accumsan, urna magna varius tortor, vehicula faucibus justo massa in libero.');
                            $em->persist($tmp3);
                        }

                        $em->persist($tmp2);
                    }
                }

                $em->persist($tmp);
            }            
        }
        $em->flush();
        return $this->redirectToRoute('index');
        */