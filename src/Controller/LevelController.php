<?php

namespace App\Controller;

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
  #[Route('/niveau/{name}', name: 'app_level_details')]
  public function details(): Response
  {
    return $this->render('level/index.html.twig', [
      'controller_name' => 'LevelController',
    ]);
  }
}
