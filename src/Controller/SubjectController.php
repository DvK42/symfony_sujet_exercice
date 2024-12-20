<?php

namespace App\Controller;

use App\Entity\Level;
use App\Entity\Subject;
use App\Repository\ChapiterRepository;
use App\Repository\ExerciseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SubjectController extends AbstractController
{
  #[Route('/matieres', name: 'app_subject_list')]
  public function index(): Response
  {
    return $this->render('subject/index.html.twig', [
      'controller_name' => 'SubjectController',
    ]);
  }

  #[Route('/matiere/{id}', name: 'app_subject_details')]

  public function details(
    Subject $subject,
    ExerciseRepository $exerciseRepository,
    ChapiterRepository $chapiterRepository,
  ): Response {
    $levels = $chapiterRepository->findLevelsBySubject($subject);

    return $this->render('subject/details.html.twig', [
      'subject' => $subject,
      'levels' => $levels,
    ]);
  }

  #[Route('/matiere/{subject}/{level}', name: 'app_subject_level_detail')]
  public function levelDetails(
    Subject $subject,
    Level $level,
    ExerciseRepository $exerciseRepository
  ): Response {
    $exercises = $exerciseRepository->findBySubjectAndLevel($subject, $level);

    return $this->render('subject/level_detail.html.twig', [
      'subject' => $subject,
      'level' => $level,
      'exercises' => $exercises,
    ]);

  }
}
