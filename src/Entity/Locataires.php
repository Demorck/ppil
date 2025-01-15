<?php

namespace App\Entity;

use App\Repository\LocatairesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocatairesRepository::class)]
class Locataires extends Utilisateurs
{
    /**
     * @var Collection<int, Locations>
     */
    #[ORM\OneToMany(targetEntity: Locations::class, mappedBy: 'locataire')]
    private Collection $locations;

    public function __construct()
    {
        $this->locations = new ArrayCollection();
    }

    /**
     * @return Collection<int, Locations>
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Locations $location): static
    {
        if (!$this->locations->contains($location)) {
            $this->locations->add($location);
            $location->setLocataire($this);
        }

        return $this;
    }

    public function removeLocation(Locations $location): static
    {
        if ($this->locations->removeElement($location)) {
            // set the owning side to null (unless already changed)
            if ($location->getLocataire() === $this) {
                $location->setLocataire(null);
            }
        }

        return $this;
    }
}
