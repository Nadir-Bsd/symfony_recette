<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use DateTimeImmutable;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    // Validate the name field
    #[Assert\Sequentially(
        [
            new Assert\NotBlank(message: 'Please enter a name'),
            new Assert\NotNull(message: 'Please let this input alone'),
            new Assert\Length(
                min: 3,
                max: 100,
                minMessage: 'The name must be at least {{ limit }} characters long',
                maxMessage: 'The name cannot be longer than {{ limit }} characters'
            )
        ]
    )]
    private ?string $name = null;

    #[ORM\Column]
    private ?DateTimeImmutable $created_at = null;

    #[ORM\Column(length: 255)]
    // Validate the description field
    #[Assert\Sequentially(
        [
            new Assert\NotBlank(message: 'Please enter a description'),
            new Assert\NotNull(message: 'Please let this input alone'),
            new Assert\Length(
                min: 10,
                max: 255,
                minMessage: 'The description must be at least {{ limit }} characters long',
                maxMessage: 'The description cannot be longer than {{ limit }} characters'
            )
        ]
    )]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    // Validate the slug field
    #[Assert\Sequentially(
        [
            new Assert\NotBlank(message: 'Please enter a slug'),
            new Assert\NotNull(message: 'Please let this input alone'),
            new Assert\Length(
                min: 3,
                max: 100,
                minMessage: 'The slug must be at least {{ limit }} characters long',
                maxMessage: 'The slug cannot be longer than {{ limit }} characters'
            )
        ]
    )]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'Recipe')]
    #[ORM\JoinColumn(nullable: true)]
    // Validate the category field
    #[Assert\NotBlank(message: 'Please select a category')]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    private ?User $user = null;

    public function __construct()
    {
        $this->created_at = new DateTimeImmutable();
    }

    public function __toString(): string
    {
        return $this->name;
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

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

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
}
