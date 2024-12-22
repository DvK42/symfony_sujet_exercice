<?php

namespace App\Controller;

use App\Entity\Chapiter;
use App\Form\ChapiterType;
use App\Repository\ChapiterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/chapiter')]
class ChapiterCrudController extends AbstractController
{
  #[Route('/', name: 'admin_chapiter_index', methods: ['GET'])]
  public function index(ChapiterRepository $chapiterRepository): Response
  {
    return $this->render('admin/chapiter/index.html.twig', [
      'chapiters' => $chapiterRepository->findAll(),
      'is_admin_page' => true,
    ]);
  }

  #[Route('/new', name: 'admin_chapiter_new', methods: ['GET', 'POST'])]
  public function new(Request $request, EntityManagerInterface $entityManager): Response
  {
    $chapiter = new Chapiter();
    $form = $this->createForm(ChapiterType::class, $chapiter);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($chapiter);
      $entityManager->flush();

      $this->addFlash('success', 'Chapitre créé avec succès.');

      return $this->redirectToRoute('admin_chapiter_index');
    }

    return $this->render('admin/chapiter/new.html.twig', [
      'form' => $form->createView(),
      'is_admin_page' => true,
    ]);
  }

  #[Route('/{id}', name: 'admin_chapiter_show', methods: ['GET'])]
  public function show(Chapiter $chapiter): Response
  {
    return $this->render('admin/chapiter/show.html.twig', [
      'chapiter' => $chapiter,
      'is_admin_page' => true,
    ]);
  }

  #[Route('/{id}/edit', name: 'admin_chapiter_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, Chapiter $chapiter, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(ChapiterType::class, $chapiter);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      $this->addFlash('success', 'Chapitre mis à jour avec succès.');

      return $this->redirectToRoute('admin_chapiter_index');
    }

    return $this->render('admin/chapiter/edit.html.twig', [
      'chapiter' => $chapiter,
      'form' => $form->createView(),
      'is_admin_page' => true,
    ]);
  }

  #[Route('/{id}', name: 'admin_chapiter_delete', methods: ['POST'])]
  public function delete(Request $request, Chapiter $chapiter, EntityManagerInterface $entityManager): Response
  {
    if ($this->isCsrfTokenValid('delete' . $chapiter->getId(), $request->request->get('_token'))) {
      $entityManager->remove($chapiter);
      $entityManager->flush();

      $this->addFlash('success', 'Chapitre supprimé avec succès.');
    }

    return $this->redirectToRoute('admin_chapiter_index');
  }
}
