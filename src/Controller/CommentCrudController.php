<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/comment')]
class CommentCrudController extends AbstractController
{
  #[Route('/', name: 'admin_comment_index', methods: ['GET'])]
  public function index(CommentRepository $commentRepository): Response
  {
    return $this->render('admin/comment/index.html.twig', [
      'comments' => $commentRepository->findAll(),
      'is_admin_page' => true,
    ]);
  }

  #[Route('/new', name: 'admin_comment_new', methods: ['GET', 'POST'])]
  public function new(Request $request, EntityManagerInterface $entityManager): Response
  {
    $comment = new Comment();
    $form = $this->createForm(CommentType::class, $comment);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($comment);
      $entityManager->flush();

      $this->addFlash('success', 'Commentaire créé avec succès.');

      return $this->redirectToRoute('admin_comment_index');
    }

    return $this->render('admin/comment/new.html.twig', [
      'form' => $form->createView(),
      'is_admin_page' => true,
    ]);
  }

  #[Route('/{id}', name: 'admin_comment_show', methods: ['GET'])]
  public function show(Comment $comment): Response
  {
    return $this->render('admin/comment/show.html.twig', [
      'comment' => $comment,
      'is_admin_page' => true,
    ]);
  }

  #[Route('/{id}/edit', name: 'admin_comment_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(CommentType::class, $comment);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      $this->addFlash('success', 'Commentaire mis à jour avec succès.');

      return $this->redirectToRoute('admin_comment_index');
    }

    return $this->render('admin/comment/edit.html.twig', [
      'form' => $form->createView(),
      'comment' => $comment,
      'is_admin_page' => true,
    ]);
  }

  #[Route('/{id}', name: 'admin_comment_delete', methods: ['POST'])]
  public function delete(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
  {
    if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
      $entityManager->remove($comment);
      $entityManager->flush();

      $this->addFlash('success', 'Commentaire supprimé avec succès.');
    }

    return $this->redirectToRoute('admin_comment_index');
  }
}
