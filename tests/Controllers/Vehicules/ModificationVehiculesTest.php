<?php

namespace App\Tests\Controllers\Vehicules;

use App\Entity\Vehicules;
use App\Tests\Helpers\UserHelper;
use App\Tests\Helpers\VehiculeHelper;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\Helpers\Utils;

class ModificationVehiculesTest extends WebTestCase
{
    private $client;
    private $userHelper;
    private $vehiculeHelper;
    private $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();

        $this->userHelper = new UserHelper($this->client, $this->entityManager);
        $this->vehiculeHelper = new VehiculeHelper($this->client, $this->entityManager);

        $user = $this->userHelper->createLocataire("test@email.com");
        $this->vehiculeHelper->createVehicule($user, 'XX-123-XX');
    }

    protected function tearDown(): void
    {
        Utils::resetDB($this->entityManager);
        parent::tearDown();
    }

    public function testModificationVehicule(): void
    {
        $user = $this->userHelper->createLocataire("Isaac@samaman.com");
        $this->userHelper->login($user);
        $vehicule = $this->vehiculeHelper->createVehicule($user, 'XX-123-YY');

        $crawler = $this->client->request('GET', '/vehicules');
        $this->assertSelectorTextContains('html', 'XX-123-YY');


        $crawler = $this->client->request('GET', '/vehicules/2');

        $form = $crawler->selectButton('modifier')->form([
            'vehicule_modif_form[marque]' => 'Renault',
            'vehicule_modif_form[modele]' => '500',
            'vehicule_modif_form[immatriculation]' => 'AA-456-BB',
            'vehicule_modif_form[annee]' => '1999-01-01',
            'vehicule_modif_form[nombrePlace]' => 50,
            'vehicule_modif_form[typeCarburant]' => 'Diesel',
            'vehicule_modif_form[kilometrage]' => 5,
        ]);

        $this->client->submit($form);

        // Vérifie la redirection (et si ça update la liste)
        $this->assertResponseRedirects('/vehicules');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('html', 'AA-456-BB');
        $this->assertSelectorTextNotContains('html', 'Renault');

        $entityManager = static::getContainer()->get('doctrine.orm.entity_manager');
        $vehicule = $entityManager->getRepository(Vehicules::class)->findOneBy(['marque' => 'Peugeot', 'modele' => '208', 'proprietaire' => $user]);

        $this->assertEquals('AA-456-BB', $vehicule->getImmatriculation());
    }

    public function testPasLoginVehicule(): void
    {
        $crawler = $this->client->request('GET', '/vehicules/1');
        $this->assertResponseRedirects('/login');
    }

    public function testPasBonUserVehicule(): void
    {
        $user = $this->userHelper->createLocataire("isaac@etsamaman.com");
        $this->userHelper->login($user);
        $this->client->request('GET', '/vehicules/1');
        $this->assertResponseRedirects('/vehicules');
    }

    public function testIdInexistant(): void
    {
        $user = $this->userHelper->createLocataire("isaac@etsamaman.com");
        $this->userHelper->login($user);

        $this->client->request('GET', '/vehicules/100');
        $this->assertResponseRedirects('/vehicules');

        $this->client->request('GET', '/vehicules/completementPasBonLà');
        $this->assertResponseRedirects('/vehicules');
    }
}
