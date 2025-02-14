<?php

namespace App\Tests\Controllers\Vehicules;

use App\Entity\Vehicules;
use App\Tests\Helpers\UserHelper;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreationVehiculesTest extends WebTestCase
{
    private $client;
    private $userHelper;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $entityManager = static::getContainer()->get('doctrine')->getManager();
        $this->userHelper = new UserHelper($this->client, $entityManager);
    }

    public function testSubmitVehiculeLocataire(): void
    {
        $user = $this->userHelper->createLocataire("test@email.com");
        $this->userHelper->login($user);
        $crawler = $this->client->request('GET', '/vehicules/ajoutVehicule');

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
        $this->assertSelectorTextContains('html', 'XX-123-YY');

        $entityManager = static::getContainer()->get('doctrine.orm.entity_manager');
        $vehicule = $entityManager->getRepository(Vehicules::class)->findOneBy(['marque' => 'Peugeot', 'modele' => '208']);

        // Vérifie le véhicule dans la base de données
        $this->assertNotNull($vehicule, 'Le véhicule n\'a pas été trouvé dans la base de données');
        $this->assertEquals('Peugeot', $vehicule->getMarque());
        $this->assertEquals('208', $vehicule->getModele());
        $this->assertEquals(new DateTime('2025-01-01'), $vehicule->getAnnee());
        $this->assertEquals('XX-123-YY', $vehicule->getImmatriculation());
        $this->assertEquals('Essence', $vehicule->getTypeCarburant());
        $this->assertEquals(5, $vehicule->getNombrePlace());
        $this->assertEquals(1000000, $vehicule->getKilometrage());

    }

    public function testPasLoginVehicules(): void
    {
        $this->client->request('GET', '/vehicules/ajoutVehicule');
        $this->assertResponseRedirects('/login');

        $crawler = $this->client->request('GET', '/vehicules');
        $this->assertResponseRedirects('/login');
    }
}
