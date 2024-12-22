<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/user')]
class UserCrudController extends AbstractController
{
  #[Route('/', name: 'admin_user_index', methods: ['GET'])]
  public function index(UserRepository $userRepository): Response
  {
    return $this->render('admin/user/index.html.twig', [
      'users' => $userRepository->findAll(),
      'is_admin_page' => true,
    ]);
  }

  #[Route('/new', name: 'admin_user_new', methods: ['GET', 'POST'])]
  public function new(Request $request, EntityManagerInterface $entityManager): Response
  {
    $user = new User();
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // Hashage du mot de passe si nécessaire
      // $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPlainPassword());
      // $user->setPassword($hashedPassword);

      $entityManager->persist($user);
      $entityManager->flush();

      $this->addFlash('success', 'Utilisateur créé avec succès.');

      return $this->redirectToRoute('admin_user_index');
    }

    return $this->render('admin/user/new.html.twig', [
      'form' => $form->createView(),
      'is_admin_page' => true,
    ]);
  }

  #[Route('/{id}', name: 'admin_user_show', methods: ['GET'])]
  public function show(User $user): Response
  {
    return $this->render('admin/user/show.html.twig', [
      'user' => $user,
      'is_admin_page' => true,
    ]);
  }

  #[Route('/{id}/edit', name: 'admin_user_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
  {
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $user = $form->getData();

      // Gestion du mot de passe
      if ($plainPassword = $form->get('plainPassword')->getData()) {
        $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);
      }

      $entityManager->persist($user);
      $entityManager->flush();

      $this->addFlash('success', 'Utilisateur mis à jour avec succès !');

      return $this->redirectToRoute('admin_user_index');
    }

    return $this->render('admin/user/edit.html.twig', [
      'user' => $user,
      'form' => $form->createView(),
      'is_admin_page' => true,
    ]);
  }

  #[Route('/{id}', name: 'admin_user_delete', methods: ['POST'])]
  public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
  {
    if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
      foreach ($user->getExercises() as $exercise) {
        $entityManager->remove($exercise);
      }

      $entityManager->remove($user);
      $entityManager->flush();

      $this->addFlash('success', 'Utilisateur supprimé avec succès.');
    }

    return $this->redirectToRoute('admin_user_index');
  }

  #[Route('/{id}/toggle-ban', name: 'admin_user_toggle_ban', methods: ['POST'])]
  public function toggleBan(
    Request $request,
    User $user,
    EntityManagerInterface $entityManager
  ): Response {
    if (!$this->isCsrfTokenValid('toggle-ban-' . $user->getId(), $request->request->get('_token'))) {
      $this->addFlash('error', 'Action non autorisée.');
      return $this->redirectToRoute('admin_user_index');
    }

    // Vérifier si l'utilisateur est déjà banni
    if (in_array('ROLE_BANNED', $user->getRoles())) {
      // Débloquer l'utilisateur (remettre ROLE_USER)
      $user->setRoles(['ROLE_USER']);
      $this->addFlash('success', 'Utilisateur débloqué avec succès.');
    } else {
      // Bloquer l'utilisateur (attribuer uniquement ROLE_BANNED)
      $user->setRoles(['ROLE_BANNED']);
      $this->addFlash('success', 'Utilisateur bloqué avec succès.');
    }

    $entityManager->persist($user);
    $entityManager->flush();

    return $this->redirectToRoute('admin_user_index');
  }
}
