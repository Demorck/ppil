<?php

namespace App\Entity;

use App\Repository\JuristeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JuristeRepository::class)]
class Juriste extends Utilisateurs
{
    /**
     * @var Collection<int, Litiges>
     */
    #[ORM\OneToMany(targetEntity: Litiges::class, mappedBy: 'juriste')]
    private Collection $litiges;

    public function __construct()
    {
        $this->litiges = new ArrayCollection();
    }

    /**
     * @return Collection<int, Litiges>
     */
    public function getLitiges(): Collection
    {
        return $this->litiges;
    }

    public function addLitige(Litiges $litige): static
    {
        if (!$this->litiges->contains($litige)) {
            $this->litiges->add($litige);
            $litige->setJuriste($this);
        }

        return $this;
    }

    public function removeLitige(Litiges $litige): static
    {
        if ($this->litiges->removeElement($litige)) {
            // set the owning side to null (unless already changed)
            if ($litige->getJuriste() === $this) {
                $litige->setJuriste(null);
            }
        }

        return $this;
    }
}
