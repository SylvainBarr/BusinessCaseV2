<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
#[ApiResource]
class Genre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'genre', targetEntity: Groupe::class)]
    private Collection $groupes;

    #[ORM\ManyToMany(targetEntity: self::class)]
    private Collection $parentsGenres;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        $this->parentsGenres = new ArrayCollection();
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
     * @return Collection<int, Groupe>
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): static
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes->add($groupe);
            $groupe->setGenre($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): static
    {
        if ($this->groupes->removeElement($groupe)) {
            // set the owning side to null (unless already changed)
            if ($groupe->getGenre() === $this) {
                $groupe->setGenre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getParentsGenres(): Collection
    {
        return $this->parentsGenres;
    }

    public function addParentsGenre(self $parentsGenre): static
    {
        if (!$this->parentsGenres->contains($parentsGenre)) {
            $this->parentsGenres->add($parentsGenre);
        }

        return $this;
    }

    public function removeParentsGenre(self $parentsGenre): static
    {
        $this->parentsGenres->removeElement($parentsGenre);

        return $this;
    }
}
