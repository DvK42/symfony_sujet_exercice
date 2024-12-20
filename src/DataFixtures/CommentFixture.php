<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Exercise;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentFixture extends Fixture implements DependentFixtureInterface
{
  public function load(ObjectManager $manager): void
  {
    $faker = Factory::create('fr_FR');

    $exercises = $manager->getRepository(Exercise::class)->findAll();

    $users = $manager->getRepository(User::class)->findAll();
    $regularUsers = array_filter($users, function (User $user) {
      return in_array('ROLE_USER', $user->getRoles()) &&
        !in_array('ROLE_ADMIN', $user->getRoles()) &&
        !in_array('ROLE_BANNED', $user->getRoles());
    });

    $commentCount = 30;

    while ($commentCount > 0 && !empty($exercises)) {
      $exercise = $faker->randomElement($exercises);

      $comment = new Comment();
      $comment->setContent($faker->paragraph())
        ->setExercise($exercise);

      if (!empty($regularUsers)) {
        $author = $faker->randomElement($regularUsers);
        $comment->setUser($author);
      }

      $manager->persist($comment);

      $commentCount--;
    }

    $manager->flush();
  }

  public function getDependencies(): array
  {
    return [
      UserFixture::class,
      ExerciseFixture::class,
    ];
  }
}
