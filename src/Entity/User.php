<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 180)]
  #[Assert\NotBlank(message: 'L\'email est requis.')]
  #[Assert\Email(message: 'Veuillez saisir une adresse email valide.')]

  private ?string $email = null;

  /**
   * @var list<string> The user roles
   */
  #[ORM\Column]
  private array $roles = ['ROLE_USER'];

  /**
   * @var string The hashed password
   */
  #[ORM\Column]
  #[Assert\NotBlank(message: 'Le mot de passe est requis.')]
  #[Assert\Length(min: 6, minMessage: 'Le mot de passe doit contenir au moins {{ limit }} caractères.')]

  private ?string $password = null;

  #[Assert\NotBlank(message: 'Le mot de passe est requis.', groups: ['registration', 'password_update'])]
  #[Assert\Length(min: 6, minMessage: 'Le mot de passe doit contenir au moins {{ limit }} caractères.', groups: ['registration', 'password_update'])]
  private ?string $plainPassword = null;


  #[ORM\Column(length: 255)]
  private ?string $firstName = null;

  #[ORM\Column(length: 255)]
  private ?string $lastName = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $studyLevel = null;

  /**
   * @var Collection<int, Exercise>
   */
  #[ORM\OneToMany(targetEntity: Exercise::class, mappedBy: 'author')]
  private Collection $exercises;

  /**
   * @var Collection<int, Comment>
   */
  #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'user', orphanRemoval: true)]
  private Collection $comments;

  /**
   * @var Collection<int, UserSolution>
   */
  #[ORM\OneToMany(targetEntity: UserSolution::class, mappedBy: 'user', orphanRemoval: true)]
  private Collection $userSolutions;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $resetToken = null;

  public function __construct()
  {
    $this->exercises = new ArrayCollection();
    $this->comments = new ArrayCollection();
    $this->userSolutions = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getEmail(): ?string
  {
    return $this->email;
  }

  public function setEmail(string $email): static
  {
    $this->email = $email;

    return $this;
  }

  /**
   * A visual identifier that represents this user.
   *
   * @see UserInterface
   */
  public function getUserIdentifier(): string
  {
    return (string) $this->email;
  }

  /**
   * @see UserInterface
   * @return list<string>
   */
  public function getRoles(): array
  {
    $roles = $this->roles;
    // guarantee every user at least has ROLE_USER
    if (!in_array('ROLE_USER', $roles)) {
      $roles[] = 'ROLE_USER';
    }

    return array_unique($roles);
  }

  /**
   * @param list<string> $roles
   */
  public function setRoles(array $roles): static
  {
    $this->roles = $roles;

    return $this;
  }

  /**
   * @see PasswordAuthenticatedUserInterface
   */
  public function getPassword(): ?string
  {
    return $this->password;
  }

  public function setPassword(string $password): static
  {
    $this->password = $password;

    return $this;
  }

  /**
   * @see UserInterface
   */
  public function eraseCredentials(): void
  {
    // If you store any temporary, sensitive data on the user, clear it here
    // $this->plainPassword = null;
  }

  public function getFirstName(): ?string
  {
    return $this->firstName;
  }

  public function setFirstName(string $firstName): static
  {
    $this->firstName = $firstName;

    return $this;
  }

  public function getLastName(): ?string
  {
    return $this->lastName;
  }

  public function setLastName(string $lastName): static
  {
    $this->lastName = $lastName;

    return $this;
  }

  public function getStudyLevel(): ?string
  {
    return $this->studyLevel;
  }

  public function setStudyLevel(?string $studyLevel): static
  {
    $this->studyLevel = $studyLevel;

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
      $exercise->setAuthor($this);
    }

    return $this;
  }

  public function removeExercise(Exercise $exercise): static
  {
    if ($this->exercises->removeElement($exercise)) {
      // set the owning side to null (unless already changed)
      if ($exercise->getAuthor() === $this) {
        $exercise->setAuthor(null);
      }
    }

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
      $comment->setUser($this);
    }

    return $this;
  }

  public function removeComment(Comment $comment): static
  {
    if ($this->comments->removeElement($comment)) {
      // set the owning side to null (unless already changed)
      if ($comment->getUser() === $this) {
        $comment->setUser(null);
      }
    }

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
      $userSolution->setUser($this);
    }

    return $this;
  }

  public function removeUserSolution(UserSolution $userSolution): static
  {
    if ($this->userSolutions->removeElement($userSolution)) {
      // set the owning side to null (unless already changed)
      if ($userSolution->getUser() === $this) {
        $userSolution->setUser(null);
      }
    }

    return $this;
  }

  public function getResetToken(): ?string
  {
    return $this->resetToken;
  }

  public function setResetToken(?string $resetToken): static
  {
    $this->resetToken = $resetToken;

    return $this;
  }

  /**
   * Get the value of plainPassword
   */
  public function getPlainPassword(): string
  {
    return $this->plainPassword;
  }

  /**
   * Set the value of plainPassword
   *
   * @return  self
   */
  public function setPlainPassword($plainPassword): static
  {
    $this->plainPassword = $plainPassword;

    return $this;
  }
}
