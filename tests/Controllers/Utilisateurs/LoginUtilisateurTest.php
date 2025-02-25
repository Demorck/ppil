<?php

declare(strict_types=1);

namespace App\Tests\Controllers\Utilisateurs;

use App\Entity\Utilisateurs;
use App\Tests\Helpers\UserHelper;
use App\Tests\Helpers\Utils;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use function Symfony\Component\Translation\t;

class LoginUtilisateurTest extends WebTestCase
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

    public function testLoginUtilisateur(): void
    {
        $crawler = $this->client->request('GET', '/login');
        $user = $this->userHelper->createLocataire("test@email.com");

        $form = $crawler->selectButton('login')->form([
            '_username' => $user->getEmail(),
            '_password' => 'password',
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/');
        $this->client->followRedirect();
    }

    public function testInvalidCredentials(): void
    {
        $crawler = $this->client->request('GET', '/login');
        $user = $this->userHelper->createLocataire("test@email.com");

        $form = $crawler->selectButton('login')->form([
            '_username' => $user->getEmail(),
            '_password' => 'mauvais mot de passe :(',
        ]);

        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertAnySelectorTextContains('.error', 'Identifiants invalides.');
    }
}
