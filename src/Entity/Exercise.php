<?php

namespace App\Entity;

use App\Repository\ExerciseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExerciseRepository::class)]
class Exercise
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  private ?string $name = null;

  #[ORM\Column(type: Types::TEXT)]
  private ?string $content = null;

  #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'exercises')]
  #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
  private ?User $author = null;

  #[ORM\ManyToOne(inversedBy: 'exercises')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Chapiter $chapiter = null;

  #[ORM\ManyToOne(inversedBy: 'exercises')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Subject $subject = null;

  #[ORM\ManyToOne(inversedBy: 'exercises')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Level $level = null;

  #[ORM\Column(type: Types::TEXT, nullable: true)]
  private ?string $solution = null;

  /**
   * @var Collection<int, Comment>
   */
  #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'exercise', orphanRemoval: true)]
  private Collection $comments;

  #[ORM\Column(length: 255, unique: true)]
  private ?string $slug = null;

  /**
   * @var Collection<int, UserSolution>
   */
  #[ORM\OneToMany(targetEntity: UserSolution::class, mappedBy: 'exercise', orphanRemoval: true)]
  private Collection $userSolutions;

  public function __construct()
  {
    $this->comments = new ArrayCollection();
    $this->userSolutions = new ArrayCollection();
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

  public function getContent(): ?string
  {
    return $this->content;
  }

  public function setContent(string $content): static
  {
    $this->content = $content;

    return $this;
  }

  public function getAuthor(): ?User
  {
    return $this->author;
  }

  public function setAuthor(?User $author): static
  {
    $this->author = $author;

    return $this;
  }

  public function getChapiter(): ?Chapiter
  {
    return $this->chapiter;
  }

  public function setChapiter(?Chapiter $chapiter): static
  {
    $this->chapiter = $chapiter;

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

  public function getLevel(): ?Level
  {
    return $this->level;
  }

  public function setLevel(?Level $level): static
  {
    $this->level = $level;

    return $this;
  }

  public function getSolution(): ?string
  {
    return $this->solution;
  }

  public function setSolution(?string $solution): static
  {
    $this->solution = $solution;

    return $this;
  }

  /**
   * @return Collection<int, Comment>
   */
  public function getComments(): Collection
  {
    return $this->comments;
  }

  public function addComment(Comment $comment): static
  {
    if (!$this->comments->contains($comment)) {
      $this->comments->add($comment);
      $comment->setExercise($this);
    }

    return $this;
  }

  public function removeComment(Comment $comment): static
  {
    if ($this->comments->removeElement($comment)) {
      // set the owning side to null (unless already changed)
      if ($comment->getExercise() === $this) {
        $comment->setExercise(null);
      }
    }

    return $this;
  }

  public function getSlug(): ?string
  {
    return $this->slug;
  }

  public function setSlug(string $slug): static
  {
    $this->slug = $slug;

    return $this;
  }

  /**
   * @return Collection<int, UserSolution>
   */
  public function getUserSolutions(): Collection
  {
    return $this->userSolutions;
  }

  public function addUserSolution(UserSolution $userSolution): static
  {
    if (!$this->userSolutions->contains($userSolution)) {
      $this->userSolutions->add($userSolution);
      $userSolution->setExercise($this);
    }

    return $this;
  }

  public function removeUserSolution(UserSolution $userSolution): static
  {
    if ($this->userSolutions->removeElement($userSolution)) {
      // set the owning side to null (unless already changed)
      if ($userSolution->getExercise() === $this) {
        $userSolution->setExercise(null);
      }
    }

    return $this;
  }
}
