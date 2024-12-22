<?php

namespace App\Controller;

use App\Repository\ExerciseRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
  #[Route('/admin', name: 'app_admin_dashboard')]
  public function index(
    ExerciseRepository $exerciseRepository,
    UserRepository $userRepository
  ): Response {
    $totalExercises = $exerciseRepository->count([]);
    $totalUsers = $userRepository->count([]);

    return $this->render('admin/index.html.twig', [
      'totalExercises' => $totalExercises,
      'totalUsers' => $totalUsers,
      'is_admin_page' => true,
    ]);
  }
}
