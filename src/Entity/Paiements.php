<?php

namespace App\Entity;

use App\Repository\PaiementsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaiementsRepository::class)]
class Paiements
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?int $statut = null;

    #[ORM\OneToOne(mappedBy: 'paiement', cascade: ['persist', 'remove'])]
    private ?Commissions $commissions = null;

    #[ORM\OneToOne(inversedBy: 'paiements', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Locations $location = null;

    #[ORM\OneToOne(inversedBy: 'paiements', cascade: ['persist', 'remove'])]
    private ?Abonnements $AbonnementId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

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

    public function getCommissions(): ?Commissions
    {
        return $this->commissions;
    }

    public function setCommissions(Commissions $commissions): static
    {
        // set the owning side of the relation if necessary
        if ($commissions->getPaiement() !== $this) {
            $commissions->setPaiement($this);
        }

        $this->commissions = $commissions;

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

    public function getAbonnementId(): ?Abonnements
    {
        return $this->AbonnementId;
    }

    public function setAbonnementId(?Abonnements $AbonnementId): static
    {
        $this->AbonnementId = $AbonnementId;

        return $this;
    }

}
