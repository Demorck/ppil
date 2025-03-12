<?php

declare(strict_types=1);

namespace App\Tests\Controllers\Accueil;

use App\Tests\Helpers\UserHelper;
use App\Tests\Helpers\Utils;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccueilTest extends WebTestCase
{


    private $client;
    private $userHelper;
    private $entityManager;

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

    public function testAccueilPasLogged(): void
    {
        // This calls KernelTestCase::bootKernel(), and creates a
        // "client" that is acting as the browser

        // Request a specific page
        $crawler = $this->client->request('GET', '/');

        // Check that the response is successful

        $this->assertResponseIsSuccessful();

        $crawler = $this->client->request('GET', '/offres');
        $this->assertResponseRedirects('/login');


    }

    public function testAccueil(): void
    {

        $crawler = $this->client->request('GET', '/');
        $user = $this->userHelper->createLocataire("test@email.com");

        $this->userHelper->login($user);

        // Check that the response is successful
        $this->assertResponseIsSuccessful();

        $crawler = $this->client->request('GET', '/offres');

        $this->assertResponseIsSuccessful();

    }


}
