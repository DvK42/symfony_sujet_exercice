<?php

namespace App\DataFixtures;

use App\Entity\Subject;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SubjectFixture extends Fixture
{
  public function load(ObjectManager $manager): void
  {
    $subjects = [
      'Mathématiques',
      'Français',
      'Histoire-Géographie',
      'Sciences de la Vie et de la Terre (SVT)',
      'Physique-Chimie',
      'Anglais',
      'Espagnol',
      'Allemand',
      'Philosophie',
      'Éducation physique et sportive (EPS)',
      'Technologie',
      'Informatique',
      'Économie',
      'Arts plastiques',
      'Musique',
      'Latin',
      'Grec ancien',
    ];

    foreach ($subjects as $subjectName) {
      $subject = new Subject();
      $subject->setName($subjectName);
      $manager->persist($subject);
    }

    $manager->flush();
  }
}
