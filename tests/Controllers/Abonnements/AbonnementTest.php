<?php

declare(strict_types=1);

namespace App\Tests\Controllers\Abonnements;

use App\Tests\Helpers\UserHelper;
use App\Tests\Helpers\Utils;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AbonnementTest extends WebTestCase
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

    public function testAbonnement(): void
    {

        $user = $this->userHelper->createLocataire("userdefou@waw.fr");
        $this->client->loginUser($user);

        $crawler = $this->client->request('GET', '/abonnement/nouveau');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Choisir')->form([
            'abonnement[type]' => '1',
        ]);
        $this->client->submit($form);
        $entityManager = static::getContainer()->get('doctrine.orm.entity_manager');
        $abonnement = $entityManager->getRepository('App\Entity\Abonnements')->findOneBy(['type' => '1']);
        $this->assertNotNull($abonnement, 'L\'abonnement n\'a pas été trouvé dans la base de données.');
        $crawler = $this->client->request('GET', '/abonnement');
        $this->assertResponseIsSuccessful();
        $this->assertAnySelectorTextContains('.container .flex.flex-row.items-center .flex-1.px-2', 'Type : 1');

    }

    public function testAbonnementPasCo(): void
    {

        $this->client->request('GET', '/abonnement');
        $this->assertResponseRedirects();
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Connexion');
    }
}
