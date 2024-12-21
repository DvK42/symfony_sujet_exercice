<?php

namespace App\DataFixtures;

use App\Entity\Subject;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\AsciiSlugger;

class SubjectFixture extends Fixture
{
  public function load(ObjectManager $manager): void
  {
    $subjects = [
      'Mathématiques',
      'Français',
      'Histoire-Géographie',
      'Sciences de la Vie et de la Terre',
      'Physique-Chimie',
      'Anglais',
      'Espagnol',
      'Allemand',
      'Philosophie',
      'Éducation physique et sportive',
      'Technologie',
      'Informatique',
      'Économie',
      'Arts plastiques',
      'Musique',
      'Latin',
      'Grec ancien',
    ];

    foreach ($subjects as $subjectName) {
      $slugger = new AsciiSlugger();
      $subjectSlug = $slugger->slug($subjectName, '-')->lower();
      $subject = new Subject();
      $subject
        ->setName($subjectName)
        ->setSlug($subjectSlug);
      $manager->persist($subject);
    }

    $manager->flush();
  }
}
