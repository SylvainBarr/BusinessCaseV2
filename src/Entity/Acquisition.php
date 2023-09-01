<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

use App\Repository\AcquisitionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AcquisitionRepository::class)]
#[ApiResource(
    collectionOperations: [
        'post' => [
            'denormalization_context' => [
                'groups' => 'acquisition:post'
            ]
        ],
        'get' => [
            'normalization_context' => [
                'groups' => 'acquisition:list'
            ]
        ],
    ],
    itemOperations: [
        'get'=> [
            'normalization_context' => [
                'groups' => 'acquisition:item'
            ],
        ],
        ]
)]
#[ApiFilter(BooleanFilter::class, properties: ['isSold'])]
#[ApiFilter(
    SearchFilter::class, properties: [
    'nft.id' => 'exact',
    'user.id' => 'exact',
    'isSold' => 'exact'
],
)]
class Acquisition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['nft:item', 'nft:list', 'acquisition:list', 'acquisition:item', 'user:item', 'acquisition:post'])]
    private ?float $value = null;

    #[ORM\Column]
    #[Groups(['nft:item', 'nft:list', 'acquisition:list', 'acquisition:item'])]
    private ?bool $isSold = false;

    #[ORM\ManyToOne(inversedBy: 'acquisitions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['nft:item', 'nft:list', 'acquisition:list', 'acquisition:item', 'acquisition:post'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'acquisitions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['acquisition:list', 'acquisition:item', 'user:item', 'acquisition:post'])]
    private ?Nft $nft = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(?float $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function isIsSold(): ?bool
    {
        return $this->isSold;
    }

    public function setIsSold(bool $isSold): static
    {
        $this->isSold = $isSold;

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
