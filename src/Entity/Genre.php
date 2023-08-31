<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => 'genre:list'
            ]
        ],
    ],
    itemOperations: [
        'get'=> [
            'normalization_context' => [
                'groups' => 'genre:item'
            ],
        ],
    ]
)]
class Genre implements SlugInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom doit être renseignée")]
    #[Groups(['genre:list', 'genre:item', 'groupe:list', 'groupe:item', 'nft:item', 'nft:list'])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'genre', targetEntity: Groupe::class)]
    #[Groups(['genre:list', 'genre:item'])]
    private Collection $groupes;

    #[ORM\ManyToMany(targetEntity: self::class)]
    #[Groups(['genre:list', 'genre:item'])]
    private Collection $parentsGenres;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['genre:list', 'genre:item', 'groupe:list', 'groupe:item', 'nft:item', 'nft:list'])]
    private ?string $slug = null;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}
