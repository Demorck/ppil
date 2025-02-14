<?php

namespace App\Tests\Controllers\Vehicules;

use App\Entity\Vehicules;
use App\Tests\Helpers\UserHelper;
use App\Tests\Helpers\VehiculeHelper;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ModificationVehiculesTest extends WebTestCase
{
    private $client;
    private $userHelper;
    private $vehiculeHelper;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $entityManager = static::getContainer()->get('doctrine')->getManager();
        $this->userHelper = new UserHelper($this->client, $entityManager);

        $this->vehiculeHelper = new VehiculeHelper($this->client, $entityManager);

        $user = $this->userHelper->createLocataire("test@email.com");
        $this->vehiculeHelper->createVehicule($user, 'XX-123-YY');

    }

    public function testModificationVehicule(): void
    {
        $user = $this->userHelper->createLocataire("Isaac@samaman.com");
        $this->userHelper->login($user);
        $vehicule = $this->vehiculeHelper->createVehicule($user, 'XX-123-YY');

        $crawler = $this->client->request('GET', '/vehicules/');

        $form = $crawler->selectButton('ajouter')->form([
            'vehicule_form[marque]' => 'Peugeot',
            'vehicule_form[modele]' => '208',
            'vehicule_form[immatriculation]' => 'XX-123-YY',
            'vehicule_form[annee]' => '2025-01-01',
            'vehicule_form[nombrePlace]' => 5,
            'vehicule_form[typeCarburant]' => 'Essence',
            'vehicule_form[kilometrage]' => 1000000,
        ]);

        $this->client->submit($form);

        // Vérifie la redirection (et si ça update la liste)
        $this->assertResponseRedirects('/vehicules');
        $this->client->followRedirect();

    }

    public function testPasLoginVehicule(): void
    {
        $crawler = $this->client->request('GET', '/vehicule/1');
        $this->assertResponseRedirects('/login');
    }

    public function testPasBonUserVehicule(): void
    {
        $user = $this->userHelper->createLocataire("isaac@etsamaman.com");
        $this->userHelper->login($user);
        $this->client->request('GET', '/vehicule/1');
        $this->assertResponseRedirects('vehicules');
    }

    public function testIdInexistant(): void
    {
        $user = $this->userHelper->createLocataire("isaac@etsamaman.com");
        $this->userHelper->login($user);

        $this->client->request('GET', '/vehicule/100');
        $this->assertResponseRedirects('vehicules');

        $this->client->request('GET', '/vehicule/completementPasBonLà');
        $this->assertResponseRedirects('vehicules');
    }
}
