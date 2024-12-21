<?php

namespace App\Controller;

use App\Repository\ChapiterRepository;
use App\Repository\LevelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LevelController extends AbstractController
{
  #[Route('/niveaux', name: 'app_level_list')]
  public function index(): Response
  {
    return $this->render('level/index.html.twig', [
      'controller_name' => 'LevelController',
    ]);
  }
  #[Route('/niveau/{slug}', name: 'app_level_details')]
  public function details(
    string $slug,
    LevelRepository $levelRepository,
    ChapiterRepository $chapiterRepository,
  ): Response {
    // Récupérer la matière via son slug
    $level = $levelRepository->findOneBySlug($slug);

    if (!$level) {
      throw $this->createNotFoundException('Le niveau d\'étude demandé est introuvable.');
    }

    // Récupérer les niveaux associés à la matière
    $subjects = $chapiterRepository->findSubjectsByLevel($level);

    return $this->render('level/details.html.twig', [
      'subjects' => $subjects,
      'level' => $level,
    ]);
  }
}
