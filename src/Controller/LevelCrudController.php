<?php

namespace App\Controller;

use App\Entity\Level;
use App\Form\LevelType;
use App\Repository\LevelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/level')]
class LevelCrudController extends AbstractController
{
  #[Route('/', name: 'admin_level_index', methods: ['GET'])]
  public function index(LevelRepository $levelRepository): Response
  {
    return $this->render('admin/level/index.html.twig', [
      'levels' => $levelRepository->findAll(),
      'is_admin_page' => true,
    ]);
  }

  #[Route('/new', name: 'admin_level_new', methods: ['GET', 'POST'])]
  public function new(Request $request, EntityManagerInterface $entityManager): Response
  {
    $level = new Level();
    $form = $this->createForm(LevelType::class, $level);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($level);
      $entityManager->flush();

      $this->addFlash('success', 'Niveau créé avec succès.');

      return $this->redirectToRoute('admin_level_index');
    }

    return $this->render('admin/level/new.html.twig', [
      'form' => $form->createView(),
      'is_admin_page' => true,
    ]);
  }

  #[Route('/{id}', name: 'admin_level_show', methods: ['GET'])]
  public function show(Level $level): Response
  {
    return $this->render('admin/level/show.html.twig', [
      'level' => $level,
      'is_admin_page' => true,
    ]);
  }

  #[Route('/{id}/edit', name: 'admin_level_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, Level $level, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(LevelType::class, $level);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      $this->addFlash('success', 'Niveau mis à jour avec succès.');

      return $this->redirectToRoute('admin_level_index');
    }

    return $this->render('admin/level/edit.html.twig', [
      'form' => $form->createView(),
      'level' => $level,
      'is_admin_page' => true,
    ]);
  }

  #[Route('/{id}', name: 'admin_level_delete', methods: ['POST'])]
  public function delete(Request $request, Level $level, EntityManagerInterface $entityManager): Response
  {
    if ($this->isCsrfTokenValid('delete' . $level->getId(), $request->request->get('_token'))) {
      $entityManager->remove($level);
      $entityManager->flush();

      $this->addFlash('success', 'Niveau supprimé avec succès.');
    }

    return $this->redirectToRoute('admin_level_index');
  }
}
