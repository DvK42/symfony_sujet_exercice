<?php

namespace App\Entity;

use App\Repository\ChapiterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChapiterRepository::class)]
class Chapiter
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  private ?string $name = null;

  #[ORM\ManyToOne(inversedBy: 'chapiters')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Subject $subject = null;

  /**
   * @var Collection<int, Exercise>
   */
  #[ORM\OneToMany(targetEntity: Exercise::class, mappedBy: 'chapiter', orphanRemoval: true)]
  private Collection $exercises;

  #[ORM\ManyToOne(inversedBy: 'chapiters')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Level $level = null;

  public function __construct()
  {
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

  public function getSubject(): ?Subject
  {
    return $this->subject;
  }

  public function setSubject(?Subject $subject): static
  {
    $this->subject = $subject;

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
      $exercise->setChapiter($this);
    }

    return $this;
  }

  public function removeExercise(Exercise $exercise): static
  {
    if ($this->exercises->removeElement($exercise)) {
      // set the owning side to null (unless already changed)
      if ($exercise->getChapiter() === $this) {
        $exercise->setChapiter(null);
      }
    }

    return $this;
  }

  public function getLevel(): ?Level
  {
    return $this->level;
  }

  public function setLevel(?Level $level): static
  {
    $this->level = $level;

    return $this;
  }
}
