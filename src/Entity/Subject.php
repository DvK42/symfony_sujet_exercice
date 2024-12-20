<?php

namespace App\Entity;

use App\Repository\SubjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubjectRepository::class)]
class Subject
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  private ?string $name = null;

  /**
   * @var Collection<int, Chapiter>
   */
  #[ORM\OneToMany(targetEntity: Chapiter::class, mappedBy: 'subject', orphanRemoval: true)]
  private Collection $chapiters;

  /**
   * @var Collection<int, Exercise>
   */
  #[ORM\OneToMany(targetEntity: Exercise::class, mappedBy: 'subject', orphanRemoval: true)]
  private Collection $exercises;

  public function __construct()
  {
    $this->chapiters = new ArrayCollection();
    $this->exercises = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(string $name): static
  {
    $this->name = $name;

    return $this;
  }

  /**
   * @return Collection<int, Chapiter>
   */
  public function getChapiters(): Collection
  {
    return $this->chapiters;
  }

  public function addChapiter(Chapiter $chapiter): static
  {
    if (!$this->chapiters->contains($chapiter)) {
      $this->chapiters->add($chapiter);
      $chapiter->setSubject($this);
    }

    return $this;
  }

  public function removeChapiter(Chapiter $chapiter): static
  {
    if ($this->chapiters->removeElement($chapiter)) {
      // set the owning side to null (unless already changed)
      if ($chapiter->getSubject() === $this) {
        $chapiter->setSubject(null);
      }
    }

    return $this;
  }

  /**
   * @return Collection<int, Exercise>
   */
  public function getExercises(): Collection
  {
    return $this->exercises;
  }

  public function addExercise(Exercise $exercise): static
  {
    if (!$this->exercises->contains($exercise)) {
      $this->exercises->add($exercise);
      $exercise->setSubject($this);
    }

    return $this;
  }

  public function removeExercise(Exercise $exercise): static
  {
    if ($this->exercises->removeElement($exercise)) {
      // set the owning side to null (unless already changed)
      if ($exercise->getSubject() === $this) {
        $exercise->setSubject(null);
      }
    }

    return $this;
  }
}
