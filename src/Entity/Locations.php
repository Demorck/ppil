<?php

namespace App\Entity;

use App\Repository\LocationsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationsRepository::class)]
class Locations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column]
    private ?int $statut = null;

    #[ORM\Column(nullable: true)]
    private ?int $kilometre = null;

    #[ORM\OneToOne(mappedBy: 'location', cascade: ['persist', 'remove'])]
    private ?Paiements $paiements = null;

    #[ORM\OneToOne(mappedBy: 'location', cascade: ['persist', 'remove'])]
    private ?Litiges $litiges = null;

    #[ORM\ManyToOne(inversedBy: 'locations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Offres $offre = null;

    #[ORM\ManyToOne(inversedBy: 'locations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Locataires $locataire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

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

    public function getKilometre(): ?int
    {
        return $this->kilometre;
    }

    public function setKilometre(?int $kilometre): static
    {
        $this->kilometre = $kilometre;

        return $this;
    }

    public function getPaiements(): ?Paiements
    {
        return $this->paiements;
    }

    public function setPaiements(Paiements $paiements): static
    {
        // set the owning side of the relation if necessary
        if ($paiements->getLocation() !== $this) {
            $paiements->setLocation($this);
        }

        $this->paiements = $paiements;

        return $this;
    }

    public function getLitiges(): ?Litiges
    {
        return $this->litiges;
    }

    public function setLitiges(Litiges $litiges): static
    {
        // set the owning side of the relation if necessary
        if ($litiges->getLocation() !== $this) {
            $litiges->setLocation($this);
        }

        $this->litiges = $litiges;

        return $this;
    }

    public function getOffre(): ?Offres
    {
        return $this->offre;
    }

    public function setOffre(?Offres $offre): static
    {
        $this->offre = $offre;

        return $this;
    }

    public function getLocataire(): ?Locataires
    {
        return $this->locataire;
    }

    public function setLocataire(?Locataires $locataire): static
    {
        $this->locataire = $locataire;

        return $this;
    }
}
