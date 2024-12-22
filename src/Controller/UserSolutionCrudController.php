<?php

namespace App\Controller;

use App\Entity\UserSolution;
use App\Form\UserSolutionType;
use App\Repository\UserSolutionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/user-solution')]
class UserSolutionCrudController extends AbstractController
{
  #[Route('/', name: 'admin_user_solution_index', methods: ['GET'])]
  public function index(UserSolutionRepository $userSolutionRepository): Response
  {
    return $this->render('admin/user_solution/index.html.twig', [
      'user_solutions' => $userSolutionRepository->findAll(),
      'is_admin_page' => true,
    ]);
  }

  #[Route('/new', name: 'admin_user_solution_new', methods: ['GET', 'POST'])]
  public function new(Request $request, EntityManagerInterface $entityManager): Response
  {
    $userSolution = new UserSolution();
    $form = $this->createForm(UserSolutionType::class, $userSolution);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($userSolution);
      $entityManager->flush();

      $this->addFlash('success', 'Solution utilisateur créée avec succès.');

      return $this->redirectToRoute('admin_user_solution_index');
    }

    return $this->render('admin/user_solution/new.html.twig', [
      'form' => $form->createView(),
      'is_admin_page' => true,
    ]);
  }

  #[Route('/{id}', name: 'admin_user_solution_show', methods: ['GET'])]
  public function show(UserSolution $userSolution): Response
  {
    return $this->render('admin/user_solution/show.html.twig', [
      'user_solution' => $userSolution,
      'is_admin_page' => true,
    ]);
  }

  #[Route('/{id}/edit', name: 'admin_user_solution_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, UserSolution $userSolution, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(UserSolutionType::class, $userSolution);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      $this->addFlash('success', 'Solution utilisateur mise à jour avec succès.');

      return $this->redirectToRoute('admin_user_solution_index');
    }

    return $this->render('admin/user_solution/edit.html.twig', [
      'form' => $form->createView(),
      'user_solution' => $userSolution,
      'is_admin_page' => true,
    ]);
  }

  #[Route('/{id}', name: 'admin_user_solution_delete', methods: ['POST'])]
  public function delete(Request $request, UserSolution $userSolution, EntityManagerInterface $entityManager): Response
  {
    if ($this->isCsrfTokenValid('delete' . $userSolution->getId(), $request->request->get('_token'))) {
      $entityManager->remove($userSolution);
      $entityManager->flush();

      $this->addFlash('success', 'Solution utilisateur supprimée avec succès.');
    }

    return $this->redirectToRoute('admin_user_solution_index');
  }
}
