<?php

namespace App\DataFixtures;

use App\Entity\Level;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\AsciiSlugger;

class LevelFixture extends Fixture
{
  public function load(ObjectManager $manager): void
  {
    $levels = [
      'CP',
      'CE1',
      'CE2',
      'CM1',
      'CM2',
      '6ème',
      '5ème',
      '4ème',
      '3ème',
      '2nde',
      '1ère',
      'Terminale',
    ];

    foreach ($levels as $levelName) {
      $level = new Level();
      $level->setName($levelName);
      $slugger = new AsciiSlugger();
      $slug = $slugger->slug($levelName, '-')->lower();
      $level->setSlug($slug);


      $manager->persist($level);
    }

    $manager->flush();
  }
}