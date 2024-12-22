<?php

namespace App\DataFixtures;

use App\Entity\Chapiter;
use App\Entity\Subject;
use App\Entity\Level;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\AsciiSlugger;

class ChapiterFixture extends Fixture implements DependentFixtureInterface
{
  public function load(ObjectManager $manager): void
  {
    $faker = Factory::create('fr_FR');

    $subjects = $manager->getRepository(Subject::class)->findAll();
    $levels = $manager->getRepository(Level::class)->findAll();

    foreach ($subjects as $subject) {
      foreach ($levels as $level) {
        for ($i = 1; $i <= 5; $i++) {
          $chapiterName = $faker->sentence(3);
          $slugger = new AsciiSlugger();
          $baseSlug = $slugger->slug($chapiterName, '-')->lower();

          // Vérifier l'unicité du slug
          $uniqueSlug = $this->generateUniqueSlug($manager, $baseSlug);

          $chapiter = new Chapiter();
          $chapiter->setName($chapiterName)
            ->setSubject($subject)
            ->setLevel($level)
            ->setSlug($uniqueSlug);

          $manager->persist($chapiter);
        }
      }
    }

    $manager->flush();
  }

  private function generateUniqueSlug(ObjectManager $manager, string $baseSlug): string
  {
    $repository = $manager->getRepository(Chapiter::class);
    $slug = $baseSlug;
    $suffix = 0;

    do {
      $existingSlug = $repository->findOneBy(['slug' => $slug]);
      if ($existingSlug) {
        $suffix++;
        $slug = $baseSlug . '-' . $suffix;
      }
    } while ($existingSlug);

    return $slug;
  }


  public function getDependencies(): array
  {
    return [
      SubjectFixture::class,
      LevelFixture::class,
    ];
  }
}
