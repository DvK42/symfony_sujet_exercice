<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
  #[Route('/', name: 'app_home')]
  public function index(): Response
  {
    if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
      return $this->render('home/index.html.twig', [
        'controller_name' => 'HomeController',
      ]);
    }

    return $this->render('landing.html.twig', [
      'controller_name' => 'HomeController',
    ]);
  }
}
