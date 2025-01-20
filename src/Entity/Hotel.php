<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\HotelUserController;
use App\Repository\HotelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;

#[ORM\Entity(repositoryClass: HotelRepository::class)]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'partial'])]
#[ApiResource(operations: [
    new GetCollection(
        uriTemplate: '/hotel_with_rooms',  // Opération personnalisée
        controller: HotelUserController::class,
        normalizationContext: ['groups' => ['hotelRoom:read']]
    ),
    new GetCollection(  // Opération GET standard pour /api/hotels
        normalizationContext: ['groups' => ['hotel:read']],
        filters: ['name']  // Permet de filtrer sur le nom
    ),
    new Get(  // Ajout d'une opération POST pour la création d'un hôtel
        uriTemplate: '/hotels/{id}', // Utilisation du paramètre {id}
        normalizationContext: ['groups' => ['hotel:read']],
    ),
    new Post(  // Ajout d'une opération POST pour la création d'un hôtel
        normalizationContext: ['groups' => ['hotel:read']],
        denormalizationContext: ['groups' => ['hotel:write']],
    ),
    new Patch(
        uriTemplate: '/hotels/{id}', // Doit inclure {id} pour cibler une ressource spécifique
        normalizationContext: ['groups' => ['hotel:read']],
        denormalizationContext: ['groups' => ['hotel:write']],
    ),
    new Delete(  // Ajout d'une opération POST pour la création d'un hôtel
        normalizationContext: ['groups' => ['hotel:read']],
        denormalizationContext: ['groups' => ['hotel:write']],
    )

])]
class Hotel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['hotel:read', 'hotel:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['hotel:read', 'hotel:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['hotel:read', 'hotel:write'])]
    private ?string $location = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['hotel:read', 'hotel:write'])]
    private ?string $description = null;

    /**
     * @var Collection<int, Room>
     */
    #[ORM\OneToMany(targetEntity: Room::class, mappedBy: 'hotel', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups(['hotel:read', 'hotel:write'])]
    private Collection $room;

    public function __construct()
    {
        $this->room = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Room>
     */
    public function getRoom(): Collection
    {
        return $this->room;
    }

    public function addRoom(Room $room): static
    {
        if (!$this->room->contains($room)) {
            $this->room->add($room);
            $room->setHotel($this);
        }

        return $this;
    }

    public function removeRoom(Room $room): static
    {
        if ($this->room->removeElement($room)) {
            // set the owning side to null (unless already changed)
            if ($room->getHotel() === $this) {
                $room->setHotel(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name ?: 'Unnamed Hotel';
    }

}
