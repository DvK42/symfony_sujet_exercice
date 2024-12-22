<?php

namespace App\Controller;

use App\Entity\Exercise;
use App\Form\ExerciseType;
use App\Repository\ExerciseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/exercise')]
class ExerciseCrudController extends AbstractController
{
  #[Route('/', name: 'admin_exercise_index', methods: ['GET'])]
  public function index(ExerciseRepository $exerciseRepository): Response
  {
    return $this->render('admin/exercise/index.html.twig', [
      'exercises' => $exerciseRepository->findAll(),
      'is_admin_page' => true,
    ]);
  }

  #[Route('/new', name: 'admin_exercise_new', methods: ['GET', 'POST'])]
  public function new(Request $request, EntityManagerInterface $entityManager): Response
  {
    $exercise = new Exercise();
    $form = $this->createForm(ExerciseType::class, $exercise);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $exercise->setAuthor($this->getUser()); // Associer l'utilisateur connecté comme auteur
      $entityManager->persist($exercise);
      $entityManager->flush();

      $this->addFlash('success', 'Exercice créé avec succès.');

      return $this->redirectToRoute('admin_exercise_index');
    }

    return $this->render('admin/exercise/new.html.twig', [
      'form' => $form->createView(),
      'is_admin_page' => true,
    ]);
  }

  #[Route('/{id}', name: 'admin_exercise_show', methods: ['GET'])]
  public function show(Exercise $exercise): Response
  {
    return $this->render('admin/exercise/show.html.twig', [
      'exercise' => $exercise,
      'is_admin_page' => true,
    ]);
  }

  #[Route('/{id}/edit', name: 'admin_exercise_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, Exercise $exercise, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(ExerciseType::class, $exercise);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      $this->addFlash('success', 'Exercice mis à jour avec succès.');

      return $this->redirectToRoute('admin_exercise_index');
    }

    return $this->render('admin/exercise/edit.html.twig', [
      'exercise' => $exercise,
      'form' => $form->createView(),
      'is_admin_page' => true,
    ]);
  }

  #[Route('/{id}', name: 'admin_exercise_delete', methods: ['POST'])]
  public function delete(Request $request, Exercise $exercise, EntityManagerInterface $entityManager): Response
  {
    if ($this->isCsrfTokenValid('delete' . $exercise->getId(), $request->request->get('_token'))) {
      $entityManager->remove($exercise);
      $entityManager->flush();

      $this->addFlash('success', 'Exercice supprimé avec succès.');
    }

    return $this->redirectToRoute('admin_exercise_index');
  }
}
