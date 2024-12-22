<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileEditType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile')]
class ProfileController extends AbstractController
{
  #[Route('/', name: 'app_profile_index', methods: ['GET'])]
  public function index(): Response
  {
    $user = $this->getUser();

    if (!$user) {
      $this->addFlash('error', 'Vous devez être connecté pour accéder à votre profil.');
      return $this->redirectToRoute('app_login');
    }

    return $this->render('profile/index.html.twig', [
      'user' => $user,
    ]);
  }

  #[Route('/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
  public function edit(
    Request $request,
    EntityManagerInterface $entityManager,
    UserPasswordHasherInterface $passwordHasher
  ): Response {
    $user = $this->getUser();

    if (!$user || !$user instanceof User) {
      $this->addFlash('error', 'Vous devez être connecté pour modifier votre profil.');
      return $this->redirectToRoute('app_login');
    }

    $form = $this->createForm(ProfileEditType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $plainPassword = $form->get('plainPassword')->getData();
      if ($plainPassword) {
        $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);
      }

      $entityManager->flush();
      $this->addFlash('success', 'Votre profil a été mis à jour avec succès.');

      return $this->redirectToRoute('app_profile_index');
    }

    return $this->render('profile/edit.html.twig', [
      'form' => $form->createView(),
    ]);
  }

}
