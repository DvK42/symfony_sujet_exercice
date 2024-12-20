<?php

namespace App\DataFixtures;

use App\Entity\Chapiter;
use App\Entity\Subject;
use App\Entity\Level;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

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
          $chapiter = new Chapiter();
          $chapiter->setName("Chapitre $i - {$subject->getName()} ({$level->getName()})")
            ->setSubject($subject)
            ->setLevel($level);

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
