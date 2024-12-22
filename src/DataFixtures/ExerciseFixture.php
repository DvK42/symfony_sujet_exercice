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
  private function generateUniqueSlug(array &$existingSlugs, string $baseSlug): string
  {
    $slug = $baseSlug;
    $suffix = 0;

    while (in_array($slug, $existingSlugs)) {
      $suffix++;
      $slug = $baseSlug . '-' . $suffix;
    }

    // Ajouter le slug généré au tableau pour éviter d'autres duplications
    $existingSlugs[] = $slug;

    return $slug;
  }

  public function load(ObjectManager $manager): void
  {
    $faker = Factory::create('fr_FR');

    $chapiters = $manager->getRepository(Chapiter::class)->findAll();
    $users = $manager->getRepository(User::class)->findAll();

    // Filtrer uniquement les utilisateurs avec ROLE_USER
    $regularUsers = array_filter($users, function (User $user) {
      return in_array('ROLE_USER', $user->getRoles()) &&
        !in_array('ROLE_ADMIN', $user->getRoles()) &&
        !in_array('ROLE_BANNED', $user->getRoles());
    });

    // Charger tous les slugs existants pour éviter les doublons
    $existingSlugs = array_map(
      fn($exercise) => $exercise->getSlug(),
      $manager->getRepository(Exercise::class)->findAll()
    );

    foreach ($chapiters as $chapiter) {
      for ($i = 1; $i <= 2; $i++) {
        $exerciseName = $faker->sentence(3);
        $slugger = new AsciiSlugger();
        $baseSlug = $slugger->slug($exerciseName, '-')->lower();

        // Générer un slug unique
        $uniqueSlug = $this->generateUniqueSlug($existingSlugs, $baseSlug);

        // Créer un nouvel exercice
        $exercise = new Exercise();
        $exercise->setName($exerciseName)
          ->setContent($faker->paragraphs(3, true))
          ->setSolution($faker->paragraphs(2, true))
          ->setChapiter($chapiter)
          ->setSubject($chapiter->getSubject())
          ->setLevel($chapiter->getLevel())
          ->setSlug($uniqueSlug);

        // Associer un auteur si des utilisateurs réguliers existent
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
