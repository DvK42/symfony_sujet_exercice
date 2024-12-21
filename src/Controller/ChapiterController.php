<?php

namespace App\Controller;

use App\Repository\ChapiterRepository;
use App\Repository\LevelRepository;
use App\Repository\SubjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ChapiterController extends AbstractController
{
  #[Route('/chapitre/{subject}/{level}', name: 'app_chapiter_list')]
  public function chapiterDetails(
    string $subject,
    string $level,
    ChapiterRepository $chapiterRepository,
    LevelRepository $levelRepository,
    SubjectRepository $subjectRepository,
  ): Response {

    $subject = $subjectRepository->findOneBySlug($subject);
    $level = $levelRepository->findOneBySlug($level);

    if (!$subject || !$level) {
      throw $this->createNotFoundException(!$level ? 'Le niveau d\'étude demandé est introuvable.' : 'La matière demandée est introuvable.');
    }

    $chapiters = $chapiterRepository->findBySubjectAndLevel($subject, $level);

    return $this->render('chapiter/list.html.twig', [
      'subject' => $subject,
      'level' => $level,
      'chapiters' => $chapiters,
    ]);

  }
}
