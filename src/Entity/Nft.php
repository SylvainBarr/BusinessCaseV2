<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\NftRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NftRepository::class)]
#[ApiResource(
    collectionOperations: [
        'post' => [
            'denormalization_context' => [
                'groups' => 'nft:post'
            ]
        ],
        'get' => [
            'normalization_context' => [
                'groups' => 'nft:list'
            ]
        ],
    ],
    itemOperations: [
        'get'=> [
            'normalization_context' => [
                'groups' => 'nft:item'
            ],
        ],
        'put',
        'delete'
    ]
)]
#[ApiFilter(
    SearchFilter::class, properties: [
    'name' => 'partial',
    'anneeAlbum' => 'exact',
    'identificationToken' => 'exact',
    'groupe.name'=> 'exact',
    'groupe.genre.name' => 'partial'
],
)]
#[ApiFilter(
    OrderFilter::class, properties: [
    'dateDrop',
    'groupe.name',
    'name',
    'groupe.genre.name'
]
)]
class Nft implements SlugInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Ce nom doit être renseigné')]
    #[Groups(['nft:post', 'nft:item', 'nft:list', 'coursNft:item', 'coursNft:list', 'acquisition:item', 'acquisition:list', 'groupe:item', 'user:item'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'image doit être renseignée obligatoirement")]
    #[Groups(['nft:post', 'nft:item', 'nft:list', 'coursNft:item', 'coursNft:list', 'acquisition:item', 'acquisition:list', 'groupe:item', 'user:item'])]
    private ?string $image = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: "Date de création obligatoire")]
    #[Assert\Date(message: "Merci de renseigner une date")]
    #[Groups(['nft:post', 'nft:item', 'nft:list'])]
    private ?\DateTimeInterface $dateDrop = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Année de l'album obligatoire")]
    #[Groups(['nft:post', 'nft:item', 'nft:list'])]
    private ?int $anneeAlbum = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le token doit être renseigné")]
    #[Groups(['nft:post', 'nft:item', 'nft:list', 'coursNft:item', 'coursNft:list', 'acquisition:item', 'acquisition:list', 'groupe:item', 'user:item'])]
    private ?string $identificationToken = null;

    #[ORM\OneToMany(mappedBy: 'nft', targetEntity: CoursNft::class)]
    #[Groups(['nft:post', 'nft:item', 'nft:list'])]
    private Collection $coursNfts;

    #[ORM\OneToMany(mappedBy: 'nft', targetEntity: Acquisition::class)]
    #[Groups(['nft:post', 'nft:item', 'nft:list'])]
    private Collection $acquisitions;

    #[ORM\ManyToOne(inversedBy: 'nfts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: "Le groupe doit être renseigné")]
    #[Groups(['nft:post', 'nft:item', 'nft:list'])]
    private ?Groupe $groupe = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['nft:post', 'nft:item', 'nft:list', 'coursNft:item', 'coursNft:list', 'acquisition:item', 'acquisition:list', 'groupe:item', 'user:item'])]
    private ?string $slug = null;

    public function __construct()
    {
        $this->coursNfts = new ArrayCollection();
        $this->acquisitions = new ArrayCollection();
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getDateDrop(): ?\DateTimeInterface
    {
        return $this->dateDrop;
    }

    public function setDateDrop(\DateTimeInterface $dateDrop): static
    {
        $this->dateDrop = $dateDrop;

        return $this;
    }

    public function getAnneeAlbum(): ?int
    {
        return $this->anneeAlbum;
    }

    public function setAnneeAlbum(int $anneeAlbum): static
    {
        $this->anneeAlbum = $anneeAlbum;

        return $this;
    }

    public function getIdentificationToken(): ?string
    {
        return $this->identificationToken;
    }

    public function setIdentificationToken(string $identificationToken): static
    {
        $this->identificationToken = $identificationToken;

        return $this;
    }

    /**
     * @return Collection<int, CoursNft>
     */
    public function getCoursNfts(): Collection
    {
        return $this->coursNfts;
    }

    public function addCoursNft(CoursNft $coursNft): static
    {
        if (!$this->coursNfts->contains($coursNft)) {
            $this->coursNfts->add($coursNft);
            $coursNft->setNft($this);
        }

        return $this;
    }

    public function removeCoursNft(CoursNft $coursNft): static
    {
        if ($this->coursNfts->removeElement($coursNft)) {
            // set the owning side to null (unless already changed)
            if ($coursNft->getNft() === $this) {
                $coursNft->setNft(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Acquisition>
     */
    public function getAcquisitions(): Collection
    {
        return $this->acquisitions;
    }

    public function addAcquisition(Acquisition $acquisition): static
    {
        if (!$this->acquisitions->contains($acquisition)) {
            $this->acquisitions->add($acquisition);
            $acquisition->setNft($this);
        }

        return $this;
    }

    public function removeAcquisition(Acquisition $acquisition): static
    {
        if ($this->acquisitions->removeElement($acquisition)) {
            // set the owning side to null (unless already changed)
            if ($acquisition->getNft() === $this) {
                $acquisition->setNft(null);
            }
        }

        return $this;
    }

    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }

    public function setGroupe(?Groupe $groupe): static
    {
        $this->groupe = $groupe;

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
