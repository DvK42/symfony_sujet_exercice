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
          $chapiterSlug = $slugger->slug($chapiterName, '-')->lower();

          $chapiter = new Chapiter();
          $chapiter->setName($chapiterName)
            ->setSubject($subject)
            ->setLevel($level)
            ->setSlug($chapiterSlug);

          $manager->persist($chapiter);
        }
      }
    }

    $manager->flush();
  }

  public function getDependencies(): array
  {
    return [
      SubjectFixture::class,
      LevelFixture::class,
    ];
  }
}
