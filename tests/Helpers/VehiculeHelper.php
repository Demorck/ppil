<?php
namespace App\Tests\Helpers;

use App\Entity\Utilisateurs;
use App\Entity\Vehicules;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VehiculeHelper
{
    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;

    public function __construct(KernelBrowser $client, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    public function createVehicule(Utilisateurs $utilisateur, String $immat): Vehicules
    {
        $vehiculeRepository = $this->entityManager->getRepository(Vehicules::class);
        $vehicule = $vehiculeRepository->findOneBy(['immatriculation' => $immat]);

        if (!$vehicule) {
            $vehicule = new Vehicules();

            $vehicule->setProprietaire($utilisateur);
            $vehicule->setAnnee(new \DateTime("01-01-1970"));
            $vehicule->setKilometrage(150000);
            $vehicule->setMarque('Peugeot');
            $vehicule->setNombrePlace(5);
            $vehicule->setModele('208');
            $vehicule->setTypeCarburant('Essence');
            $vehicule->setImmatriculation($immat);

            $this->entityManager->persist($vehicule);
            $this->entityManager->flush();
        }


        return $vehicule;
    }
}