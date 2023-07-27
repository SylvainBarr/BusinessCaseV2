<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    collectionOperations: [
        'post' => [
            'denormalization_context' => [
                'groups' => 'user:post'
            ]
        ],
    ],
    itemOperations: [
        'get'=> [
            'normalization_context' => [
                'groups' => 'user:item'
            ],
        ],
        'put',
        'delete'

    ]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['user:item', 'user:post'])]
    #[Assert\NotBlank(message: "L'adresse email doit être renseignée")]
    #[Assert\Email(message: "Merci de renseigner une adresse au bon format")]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: "Le mot de passe doit être renseigné")]
    private ?string $password = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([ 'nft:item', 'nft:list', 'acquisition:item', 'acquisition:list', 'user:item', 'user:post'])]
    private ?string $profilePicture = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le pseudonyme doit être renseignée")]
    #[Groups([ 'nft:item', 'nft:list', 'acquisition:item', 'acquisition:list', 'user:item', 'user:post'])]
    private ?string $nickname = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Acquisition::class)]
    #[Groups(['user:item'])]
    private Collection $acquisitions;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: "Le pseudonyme doit être renseignée")]
    #[Assert\Date(message: "Merci de renseigner une date au bon format")]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\ManyToOne]
    #[Groups(['user:item'])]
    private ?Address $address = null;

    public function __construct(){
        $this->createdAt = new \DateTime();
        $this->acquisitions = new ArrayCollection();
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
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?string $profilePicture): static
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }


    #[Groups(['user:item'])]
    public function isAdmin():bool{
        return in_array("ROLE_ADMIN", $this->roles);
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
            $acquisition->setUser($this);
        }

        return $this;
    }

    public function removeAcquisition(Acquisition $acquisition): static
    {
        if ($this->acquisitions->removeElement($acquisition)) {
            // set the owning side to null (unless already changed)
            if ($acquisition->getUser() === $this) {
                $acquisition->setUser(null);
            }
        }

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): static
    {
        $this->address = $address;

        return $this;
    }
}
