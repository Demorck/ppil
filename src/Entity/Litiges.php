<?php

namespace App\Entity;

use App\Repository\LitigesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LitigesRepository::class)]
class Litiges
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $statut = null;

    #[ORM\OneToOne(inversedBy: 'litiges', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Locations $location = null;

    #[ORM\ManyToOne(inversedBy: 'litiges_juriste')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateurs $juriste = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatut(): ?int
    {
        return $this->statut;
    }

    public function setStatut(int $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getLocation(): ?Locations
    {
        return $this->location;
    }

    public function setLocation(Locations $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getJuriste(): ?Utilisateurs
    {
        return $this->juriste;
    }

    public function setJuriste(?Utilisateurs $juriste): static
    {
        $this->juriste = $juriste;

        return $this;
    }
}
