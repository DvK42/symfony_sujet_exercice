<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(type: Types::TEXT)]
  #[Assert\NotBlank(message: 'Le contenu du commentaire ne peut pas être vide.')]
  #[Assert\Length(
    max: 500,
    maxMessage: 'Le commentaire ne peut pas dépasser {{ limit }} caractères.'
  )]
  private ?string $content = null;

  #[ORM\ManyToOne(inversedBy: 'comments')]
  #[ORM\JoinColumn(nullable: false)]
  private ?User $user = null;

  #[ORM\ManyToOne(inversedBy: 'comments')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Exercise $exercise = null;

  #[ORM\Column]
  private ?\DateTimeImmutable $createdAt = null;

  public function getId(): ?int
  {
    return $this->id;
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

  public function getUser(): ?User
  {
    return $this->user;
  }

  public function setUser(?User $user): static
  {
    $this->user = $user;

    return $this;
  }

  public function getExercise(): ?Exercise
  {
    return $this->exercise;
  }

  public function setExercise(?Exercise $exercise): static
  {
    $this->exercise = $exercise;

    return $this;
  }

  public function getCreatedAt(): ?\DateTimeImmutable
  {
    return $this->createdAt;
  }

  public function setCreatedAt(\DateTimeImmutable $createdAt): static
  {
    $this->createdAt = $createdAt;

    return $this;
  }
}
