<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    // Verifications des contraintes de validation
    #[Assert\Length(
        max: 100, maxMessage: 'Name ne peux pas dépasser {{ limit }} caractères',
        min: 3, minMessage: 'Name doit contenir au moins {{ limit }} caractères', 
    )]
    #[Assert\NotBlank(message: 'Name ne peux pas être vide')]
    #[Assert\NotNull(message: 'Name ne peux pas être nul')]
    private ?string $name = null;

    /**
     * @var Collection<int, Recipe>
     */
    #[ORM\OneToMany(targetEntity: Recipe::class, mappedBy: 'category', orphanRemoval: true)]
    private Collection $Recipe;

    #[ORM\Column(length: 255)]
    // Verifications des contraintes de validation
    #[Assert\Length(
        max: 100, maxMessage: 'Name ne peux pas dépasser {{ limit }} caractères',
        min: 3, minMessage: 'Name doit contenir au moins {{ limit }} caractères', 
    )]
    #[Assert\NotBlank(message: 'Name ne peux pas être vide')]
    #[Assert\NotNull(message: 'Name ne peux pas être nul')]
    private ?string $slug = null;

    public function __construct()
    {
        $this->Recipe = new ArrayCollection();
    }

    public function __toString()
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

    /**
     * @return Collection<int, Recipe>
     */
    public function getRecipe(): Collection
    {
        return $this->Recipe;
    }

    public function addRecipe(Recipe $recipe): static
    {
        if (!$this->Recipe->contains($recipe)) {
            $this->Recipe->add($recipe);
            $recipe->setCategory($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): static
    {
        if ($this->Recipe->removeElement($recipe)) {
            // set the owning side to null (unless already changed)
            if ($recipe->getCategory() === $this) {
                $recipe->setCategory(null);
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
}
