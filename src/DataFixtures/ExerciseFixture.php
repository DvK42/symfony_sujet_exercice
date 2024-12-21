<?php

namespace App\DataFixtures;

use App\Entity\Exercise;
use App\Entity\Chapiter;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\AsciiSlugger;

class ExerciseFixture extends Fixture implements DependentFixtureInterface
{
  public function load(ObjectManager $manager): void
  {
    $faker = Factory::create('fr_FR');

    $chapiters = $manager->getRepository(Chapiter::class)->findAll();

    $users = $manager->getRepository(User::class)->findAll();
    $regularUsers = array_filter($users, function (User $user) {
      return in_array('ROLE_USER', $user->getRoles()) &&
        !in_array('ROLE_ADMIN', $user->getRoles()) &&
        !in_array('ROLE_BANNED', $user->getRoles());
    });

    foreach ($chapiters as $chapiter) {
      for ($i = 1; $i <= 3; $i++) {
        $exerciseName = $faker->sentence(3);
        $slugger = new AsciiSlugger();
        $exerciseSlug = $slugger->slug($exerciseName, '-')->lower();

        $exercise = new Exercise();
        $exercise->setName($exerciseName)
          ->setContent($faker->paragraphs(3, true))
          ->setSolution($faker->paragraphs(2, true))
          ->setChapiter($chapiter)
          ->setSubject($chapiter->getSubject())
          ->setLevel($chapiter->getLevel())
          ->setSlug($exerciseSlug);

        if (!empty($regularUsers)) {
          $author = $faker->randomElement($regularUsers);
          $exercise->setAuthor($author);
        }

        $manager->persist($exercise);
      }
    }

    $manager->flush();
  }

  public function getDependencies(): array
  {
    return [
      UserFixture::class,
      ChapiterFixture::class,
      LevelFixture::class,
    ];
  }
}
