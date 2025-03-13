<?php

namespace App\Tests\Controllers\Offres;

use App\Entity\Offres;
use App\Entity\Vehicules;
use App\Tests\Helpers\UserHelper;
use App\Tests\Helpers\Utils;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ListeOffreTest extends WebTestCase
{
    private $client;
    private $entityManager;
    private $userHelper;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();
        $this->userHelper = new UserHelper($this->client, $this->entityManager);
    }

    protected function tearDown(): void
    {
        Utils::resetDB($this->entityManager); 
        parent::tearDown();
    }

    public function testAccesSansConnexion(): void
    {
        $this->client->request('GET', '/offres');
        $this->assertResponseRedirects('/login');
    }


    public function testAccesAvecConnexion(): void
    {
        $user = $this->userHelper->createLocataire("test@email.com");
        $this->userHelper->login($user);

        $vehicule = new Vehicules();
        $vehicule->setMarque('Peugeot');
        $vehicule->setModele('208');
        $vehicule->setImmatriculation('XX-123-YY');
        $vehicule->setAnnee(new \DateTime('2025-01-01'));
        $vehicule->setNombrePlace(5);
        $vehicule->setTypeCarburant('Essence');
        $vehicule->setKilometrage(100000);
        $vehicule->setProprietaire($user);

        $this->entityManager->persist($vehicule);
        $this->entityManager->flush();

        $offre = new Offres();
        $offre->setPrix(10000);
        $offre->setVehicule($vehicule);

        $this->entityManager->persist($offre);
        $this->entityManager->flush();

        $this->client->request('GET', '/offres');
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('.offre .vehicule-marque', 'Peugeot');
        $this->assertSelectorTextContains('.offre .vehicule-modele', '208');
        $this->assertSelectorTextContains('.offre .prix', '10000 €');
    }
}
