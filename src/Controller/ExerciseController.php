<?php

namespace App\Controller;

use App\Entity\Chapiter;
use App\Entity\Exercise;
use App\Repository\ChapiterRepository;
use App\Repository\ExerciseRepository;
use App\Repository\LevelRepository;
use App\Repository\SubjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
  ): Response {

    $subject = $subjectRepository->findOneBySlug($subject);
    $level = $levelRepository->findOneBySlug($level);
    $chapiter = $chapiterRepository->findOneBySlug($chapiter);

    if (!$subject || !$level || !$chapiter) {
      throw $this->createNotFoundException(!$level ? 'Le niveau d\'étude demandé est introuvable.' : (!$subject ? 'La matière demandée est introuvable.' : "Le chapitre demandé est introuvable"));
    }

    $exercises = $exerciseRepository->findByChapiterSubjectAndLevel($subject, $level, $chapiter);

    return $this->render('exercise/list.html.twig', [
      'subject' => $subject,
      'level' => $level,
      'chapiter' => $chapiter,
      'exercises' => $exercises,
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
  ): Response {

    $subject = $subjectRepository->findOneBySlug($subject);
    $level = $levelRepository->findOneBySlug($level);
    $chapiter = $chapiterRepository->findOneBySlug($chapiter);
    $exercise = $exerciseRepository->findOneBySlug($exercise);

    if (!$subject) {
      throw $this->createNotFoundException('Le niveau d\'étude demandé est introuvable.');
    }
    if (!$level) {
      throw $this->createNotFoundException('La matière demandée est introuvable.');
    }
    if (!$chapiter) {
      throw $this->createNotFoundException("Le chapitre demandé est introuvable");
    }
    if (!$exercise) {
      throw $this->createNotFoundException('L\'exercice demandé est introuvable.');
    }

    $exerciseData = $exerciseRepository->findByParamsSlug($subject, $level, $chapiter, $exercise);


    return $this->render('exercise/index.html.twig', [
      'subject' => $subject,
      'level' => $level,
      'chapiter' => $chapiter,
      'exercise' => $exerciseData,
    ]);
  }
}
