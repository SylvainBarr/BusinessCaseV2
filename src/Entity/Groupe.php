<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GroupeRepository::class)]
#[ApiResource(
    collectionOperations: [
        'post' => [
            'denormalization_context' => [
                'groups' => 'groupe:post'
            ]
        ],
        'get' => [
            'normalization_context' => [
                'groups' => 'groupe:list'
            ]
        ],
    ],
    itemOperations: [
        'get'=> [
            'normalization_context' => [
                'groups' => 'groupe:item'
            ],
        ],
    ]
)]
#[ApiFilter(
    SearchFilter::class, properties: [
    'genre.id' => 'exact',
],
)]
class Groupe implements SlugInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom doit être renseignée")]
    #[Groups(['nft:item', 'nft:list', 'genre:list', 'genre:item', 'groupe:post', 'groupe:list', 'groupe:item'])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'groupe', targetEntity: Nft::class)]
    #[Groups(['groupe:item'])]
    private Collection $nfts;

    #[ORM\ManyToOne(inversedBy: 'groupes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: "Le genre doit être renseignée")]
    #[Groups(['nft:item', 'nft:list', 'groupe:post', 'groupe:list', 'groupe:item'])]
    private ?Genre $genre = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['nft:item', 'nft:list', 'genre:list', 'genre:item', 'groupe:post', 'groupe:list', 'groupe:item'])]
    private ?string $slug = null;

    public function __construct()
    {
        $this->nfts = new ArrayCollection();
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
     * @return Collection<int, Nft>
     */
    public function getNfts(): Collection
    {
        return $this->nfts;
    }

    public function addNft(Nft $nft): static
    {
        if (!$this->nfts->contains($nft)) {
            $this->nfts->add($nft);
            $nft->setGroupe($this);
        }

        return $this;
    }

    public function removeNft(Nft $nft): static
    {
        if ($this->nfts->removeElement($nft)) {
            // set the owning side to null (unless already changed)
            if ($nft->getGroupe() === $this) {
                $nft->setGroupe(null);
            }
        }

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): static
    {
        $this->genre = $genre;

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
