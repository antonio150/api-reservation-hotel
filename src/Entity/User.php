<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Controller\UploadImageController;
use App\Dto\UserFileInput;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ApiResource(
    operations: [
        new GetCollection(  // Opération GET standard pour /api/hotels
            normalizationContext: ['groups' => ['user:read']],
            filters: ['name']  // Permet de filtrer sur le nom
        ),
        new Get(  // Ajout d'une opération POST pour la création d'un hôtel
            uriTemplate: '/users/{id}', // Utilisation du paramètre {id}
            normalizationContext: ['groups' => ['user:read']],
        ),
        new Post(
            uriTemplate: '/users',
            controller: UploadImageController::class,
            // input: UserFileInput::class
        )
    ]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    #[Groups(['user:read', 'user:write'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['user:read', 'user:write'])]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $fullname = null;

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'User')]
    #[Groups(['user:read', 'user:write'])]
    private Collection $reservation;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $filePath = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $originalName = null;

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
        $roles[] = 'ROLE_USER';

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

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(?string $fullname): static
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getReservation(): Collection
    {
        return $this->reservation;
    }

    public function addReservation(Reservation $reservation): static
    {
        if(!$this->reservation->contains($reservation))
        {
            $this->reservation->add($reservation);
            $reservation->setUser($this);
        }
        return $this;
    }

    public function removeReservation(Reservation $reservation):static 
    {
        if($this->reservation->removeElement($reservation))
        {
            if($reservation->getUser() === $this)
            {
                $reservation->setUser(null);
            }
        }

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): static
    {
        $this->filePath = $filePath;

        return $this;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(?string $originalName): static
    {
        $this->originalName = $originalName;

        return $this;
    }

    public function __toString(): string
    {
        return $this->fullname ?? '';
    }
}
