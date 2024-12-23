<?php

namespace App\Controller;

use App\Entity\Level;
use App\Entity\Subject;
use App\Repository\ChapiterRepository;
use App\Repository\ExerciseRepository;
use App\Repository\LevelRepository;
use App\Repository\SubjectRepository;
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

  #[Route('/matiere/{slug}', name: 'app_subject_details')]
  public function details(
    string $slug,
    SubjectRepository $subjectRepository,
    ChapiterRepository $chapiterRepository,
  ): Response {
    // Récupérer la matière via son slug
    $subject = $subjectRepository->findOneBySlug($slug);

    if (!$subject) {
      throw $this->createNotFoundException('La matière demandée est introuvable.');
    }

    // Récupérer les niveaux associés à la matière
    $levels = $chapiterRepository->findLevelsBySubject($subject);

    return $this->render('subject/details.html.twig', [
      'subject' => $subject,
      'levels' => $levels,
    ]);
  }
}
