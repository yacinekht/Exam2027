<?php

namespace App\Entity;

use App\Enum\AdoptionDemandeStatus;
use App\Repository\RegistrationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: RegistrationRepository::class)]
#[ORM\Table(
    name: 'registration',
    uniqueConstraints: [
        new ORM\UniqueConstraint(
            name: 'user_animal_unique',
            columns: ['user_id', 'animal_id']
        ),
    ]
)]
#[UniqueEntity(
    fields: ['user', 'animal'],
    message: 'Vous avez déjà déposé une demande pour cet animal.'
)]
class Registration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: AdoptionDemandeStatus::class)]
    private AdoptionDemandeStatus $status;

    #[ORM\ManyToOne(inversedBy: 'registrations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'registrations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Animal $animal = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    public function __construct(AdoptionDemandeStatus $status = AdoptionDemandeStatus::En_attente)
    {
        $this->status = $status;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): AdoptionDemandeStatus
    {
        return $this->status;
    }

    public function setStatus(AdoptionDemandeStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

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

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): static
    {
        $this->animal = $animal;

        return $this;
    }
}
