<?php

namespace App\Controller;

use App\Entity\Chapiter;
use App\Entity\Comment;
use App\Entity\Exercise;
use App\Entity\UserSolution;
use App\Form\CommentType;
use App\Form\UserSolutionType;
use App\Repository\ChapiterRepository;
use App\Repository\ExerciseRepository;
use App\Repository\LevelRepository;
use App\Repository\SubjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExerciseController extends AbstractController
{

  #[Route('/exercices/{subject}/{level}/{chapiter}', name: 'app_exercise_list')]
  public function exerciseDetails(
    string $subject,
    string $level,
    string $chapiter,
    ChapiterRepository $chapiterRepository,
    LevelRepository $levelRepository,
    SubjectRepository $subjectRepository,
    ExerciseRepository $exerciseRepository,
    Request $request
  ): Response {

    $subject = $subjectRepository->findOneBySlug($subject);
    $level = $levelRepository->findOneBySlug($level);
    $chapiter = $chapiterRepository->findOneBySlug($chapiter);

    $page = max(1, $request->query->getInt('page', 1)); // Page courante, 1 par défaut
    $limit = 12; // Nombre d'exercices par page
    $offset = ($page - 1) * $limit;

    // Récupérer les exercices avec pagination
    $exercises = $exerciseRepository->findByChapiterWithPagination($chapiter, $limit, $offset);

    // Compter le total des exercices
    $totalExercises = $exerciseRepository->countByChapiter($chapiter);

    return $this->render('exercise/list.html.twig', [
      'subject' => $subject,
      'level' => $level,
      'chapiter' => $chapiter,
      'exercises' => $exercises,
      'currentPage' => $page,
      'totalPages' => ceil($totalExercises / $limit),
    ]);

  }

  #[Route('/exercice/{subject}/{level}/{chapiter}/{exercise}', name: 'app_exercise_detail')]

  public function exerciseDetail(
    string $subject,
    string $level,
    string $chapiter,
    string $exercise,
    ChapiterRepository $chapiterRepository,
    LevelRepository $levelRepository,
    SubjectRepository $subjectRepository,
    ExerciseRepository $exerciseRepository,
    Request $request,
    EntityManagerInterface $entityManager
  ): Response {

    $subject = $subjectRepository->findOneBySlug($subject);
    $level = $levelRepository->findOneBySlug($level);
    $chapiter = $chapiterRepository->findOneBySlug($chapiter);
    $exercise = $exerciseRepository->findOneBySlug($exercise);

    if (!$subject || !$level || !$chapiter || !$exercise) {
      throw $this->createNotFoundException('Ressource introuvable.');
    }

    // Vérifier si l'utilisateur a déjà soumis une solution pour cet exercice
    $user = $this->getUser();
    $userSolutionRepository = $entityManager->getRepository(UserSolution::class);
    $existingSolution = $userSolutionRepository->findOneBy([
      'user' => $user,
      'exercise' => $exercise,
    ]);

    // Formulaire de réponse
    $userSolution = $existingSolution ?? new UserSolution();
    $userSolution->setExercise($exercise);
    $userSolution->setUser($user);

    $solutionForm = $this->createForm(UserSolutionType::class, $userSolution);
    $solutionForm->handleRequest($request);

    if ($solutionForm->isSubmitted() && $solutionForm->isValid()) {
      if (!$existingSolution) {
        $userSolution->setCreatedAt(new \DateTimeImmutable());
      }

      $entityManager->persist($userSolution);
      $entityManager->flush();

      $this->addFlash('success', 'Votre réponse a été enregistrée avec succès !');
      return $this->redirectToRoute('app_exercise_detail', [
        'subject' => $subject->getSlug(),
        'level' => $level->getSlug(),
        'chapiter' => $chapiter->getSlug(),
        'exercise' => $exercise->getSlug(),
      ]);
    }

    // Formulaire de commentaire
    $comment = new Comment();
    $commentForm = $this->createForm(CommentType::class, $comment);

    $commentForm->handleRequest($request);
    if ($commentForm->isSubmitted() && $commentForm->isValid()) {
      $comment->setExercise($exercise);
      $comment->setUser($this->getUser());
      $comment->setCreatedAt(new \DateTimeImmutable());

      $entityManager->persist($comment);
      $entityManager->flush();

      $this->addFlash('success', 'Votre commentaire a été ajouté avec succès !');
      return $this->redirectToRoute('app_exercise_detail', [
        'subject' => $subject->getSlug(),
        'level' => $level->getSlug(),
        'chapiter' => $chapiter->getSlug(),
        'exercise' => $exercise->getSlug(),
      ]);
    }

    $exerciseData = $exerciseRepository->findByParamsSlug($subject, $level, $chapiter, $exercise);
    $comments = $entityManager->getRepository(Comment::class)->findByExercise($exercise);

    return $this->render('exercise/index.html.twig', [
      'subject' => $subject,
      'level' => $level,
      'chapiter' => $chapiter,
      'comments' => $comments,
      'nbComments' => count($comments),
      'exercise' => $exerciseData,
      'commentForm' => $commentForm->createView(),
      'solutionForm' => $solutionForm->createView(),
    ]);
  }
}
