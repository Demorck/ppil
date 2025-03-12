<?php
namespace App\Tests\Helpers;

use App\Entity\Offres;
use App\Entity\Utilisateurs;
use App\Entity\Vehicules;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OffreHelper
{
    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;
    private Utilisateurs $utilisateurs;
    private Vehicules $vehicules;

    public function __construct(KernelBrowser $client, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;

        $this->utilisateurs = (new UserHelper($client,$entityManager))->createLocataire("locataire@offre.fr");
        $this->vehicules = (new VehiculeHelper($client, $entityManager))->createVehicule($this->utilisateurs, 'XX-126-YY');
    }

    public function createOffre(): Offres
    {
        return $this->getOffre();
    }

    private function getOffre(): Offres
    {
        $offre = new Offres();
        $offre->setVehicule($this->vehicules);
        $offre->setPrix(100);
        $offre->setStatut(0);

        $now = new \DateTime();
        $offre->setDateDebut($now);

        $now = new \DateTime();
        $offre->setDateFin($now->add(new \DateInterval('P1M')));

        $this->entityManager->persist($offre);
        $this->entityManager->flush();

        return $offre;
    }
}