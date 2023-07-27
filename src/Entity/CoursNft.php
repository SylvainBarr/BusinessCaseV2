<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\CoursNftRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CoursNftRepository::class)]
#[ApiResource(
    collectionOperations: [
        'post' => [
            'denormalization_context' => [
                'groups' => 'coursNft:post'
            ]
        ],
        'get' => [
            'normalization_context' => [
                'groups' => 'coursNft:list'
            ]
        ],
    ],
    itemOperations: [
        'get'=> [
            'normalization_context' => [
                'groups' => 'coursNft:item'
            ],
        ],
        ]
)]
#[ApiFilter(
    SearchFilter::class, properties: [
    'nft.id' => 'exact',
],
)]
class CoursNft
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['nft:item', 'nft:list', 'coursNft:item', 'coursNft:list', 'coursNft:post'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column]
    #[Groups([ 'nft:item', 'nft:list', 'coursNft:item', 'coursNft:list', 'coursNft:post'])]
    private ?float $value = null;

    #[ORM\ManyToOne(inversedBy: 'coursNfts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['coursNft:item', 'coursNft:list', 'coursNft:post'])]
    private ?Nft $nft = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getNft(): ?Nft
    {
        return $this->nft;
    }

    public function setNft(?Nft $nft): static
    {
        $this->nft = $nft;

        return $this;
    }
}
