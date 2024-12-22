<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class UserFixture extends Fixture
{
  private UserPasswordHasherInterface $passwordHasher;

  public function __construct(UserPasswordHasherInterface $passwordHasher)
  {
    $this->passwordHasher = $passwordHasher;
  }

  public function load(ObjectManager $manager): void
  {
    $faker = Factory::create('fr_FR');

    // Créer 2 administrateurs
    for ($i = 1; $i <= 2; $i++) {
      $user = new User();
      $user->setEmail("admin{$i}@example.com")
        ->setFirstName($faker->firstName)
        ->setLastName($faker->lastName)
        ->setStudyLevel(null)
        ->setRoles(['ROLE_ADMIN']);

      $hashedPassword = $this->passwordHasher->hashPassword($user, 'password');
      $user->setPassword($hashedPassword);

      $manager->persist($user);
    }

    // Créer 2 utilisateurs bannis
    for ($i = 1; $i <= 2; $i++) {
      $user = new User();
      $user->setEmail("banned{$i}@example.com")
        ->setFirstName($faker->firstName)
        ->setLastName($faker->lastName)
        ->setStudyLevel(null)
        ->setRoles(['ROLE_BANNED']);

      $hashedPassword = $this->passwordHasher->hashPassword($user, 'password');
      $user->setPassword($hashedPassword);

      $manager->persist($user);
    }

    // Créer 16 utilisateurs réguliers
    for ($i = 1; $i <= 16; $i++) {
      $user = new User();
      $user->setEmail("user{$i}@example.com")
        ->setFirstName($faker->firstName)
        ->setLastName($faker->lastName)
        ->setStudyLevel($faker->randomElement([
          'cp',
          'ce1',
          'ce2',
          'cm1',
          'cm2',
          '6eme',
          '5eme',
          '4eme',
          '3eme',
          '2nde',
          '1ere',
          'terminale',
        ]))
        ->setRoles(['ROLE_USER']);

      $hashedPassword = $this->passwordHasher->hashPassword($user, 'password');
      $user->setPassword($hashedPassword);

      $manager->persist($user);
    }

    $manager->flush();
  }
}
