<?php

namespace App\Twig;

use App\Repository\LevelRepository;
use App\Repository\SubjectRepository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class GlobalVariablesExtension extends AbstractExtension implements GlobalsInterface
{
  private SubjectRepository $subjectRepository;
  private LevelRepository $levelRepository;

  public function __construct(SubjectRepository $subjectRepository, LevelRepository $levelRepository)
  {
    $this->subjectRepository = $subjectRepository;
    $this->levelRepository = $levelRepository;
  }

  public function getGlobals(): array
  {
    return [
      'menuSubjects' => $this->subjectRepository->findMenuSubjects(),
      'levels' => $this->levelRepository->findAll(),
    ];
  }
}
