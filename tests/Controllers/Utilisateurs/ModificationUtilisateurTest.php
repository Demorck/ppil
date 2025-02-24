<?php

declare(strict_types=1);

namespace App\Tests\Controllers\Utilisateurs;

use App\Entity\Utilisateurs;
use App\Tests\Helpers\UserHelper;
use App\Tests\Helpers\Utils;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use function Symfony\Component\Translation\t;

class ModificationUtilisateurTest extends WebTestCase
{
    private $client;
    private $userHelper;
    private $entityManager;
    private $user;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->entityManager = static::getContainer()->get('doctrine')->getManager();

        $this->userHelper = new UserHelper($this->client, $this->entityManager);

        $this->user = $this->userHelper->createLocataire("waw@csuper.coom");

        $this->userHelper->login($this->user);
    }

    protected function tearDown(): void
    {
        Utils::resetDB($this->entityManager);
        parent::tearDown();
    }

    public function testModifPassword(): void
    {
        $crawler = $this->client->request('GET', '/profil');

        $user = $this->entityManager->getRepository(Utilisateurs::class)->findOneBy(['prenom' => 'Malenia']);

        $mdp = $user->getPassword();

        $form = $crawler->selectButton('Sauvegarder')->form([
            'modification_profil[password]' => '!c5x8qbD*IT@ZY9R8*3ljBey*gZv5FwZS7xd#@@htj4OC#ywRANjUjzO5wHSYzj7^%U3VWICZ&2VHHfLj7J$RG$oNFPZDRZpyu^#52P%aelZp%SLKaV#1JvSFBdJyz8B',
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/profil');
        $this->client->followRedirect();

        $entityManager = static::getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->getRepository(Utilisateurs::class)->findOneBy(['prenom' => 'Malenia']);

        $this->assertNotNull($user, 'L\'utilisateur n\'a pas été trouvé dans la base de données.');
        $this->assertNotEquals($mdp, $user->getPassword());
    }

    public function testModifPasswordCourt(): void
    {
        $crawler = $this->client->request('GET', '/profil');

        $user = $this->entityManager->getRepository(Utilisateurs::class)->findOneBy(['prenom' => 'Malenia']);

        $mdp = $user->getPassword();

        $form = $crawler->selectButton('Sauvegarder')->form([
            'modification_profil[password]' => 'aaaa',
        ]);

        $this->client->submit($form);
        $this->assertAnySelectorTextContains('.error', 'Le mot de passe est trop facile, veuillez mettre un mot de passe plus compliqué.');


        $entityManager = static::getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->getRepository(Utilisateurs::class)->findOneBy(['prenom' => 'Malenia']);

        $this->assertNotNull($user, 'L\'utilisateur n\'a pas été trouvé dans la base de données.');
        $this->assertEquals($mdp, $user->getPassword());
    }

    public function testModifPrenom(): void
    {
        $crawler = $this->client->request('GET', '/profil');

        $form = $crawler->selectButton('Sauvegarder')->form([
            'modification_profil[prenom]' => 'HelloTher',
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/profil');
        $this->client->followRedirect();

        $entityManager = static::getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->getRepository(Utilisateurs::class)->findOneBy(['prenom' => 'HelloTher']);

        $nom = $user->getPrenom();
        $this->assertNotNull($user, 'L\'utilisateur n\'a pas été trouvé dans la base de données.');
        $this->assertEquals($nom, "HelloTher");
    }

    public function testModifNom(): void
    {
        $crawler = $this->client->request('GET', '/profil');

        $form = $crawler->selectButton('Sauvegarder')->form([
            'modification_profil[nom]' => 'HelloTher',
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/profil');
        $this->client->followRedirect();

        $entityManager = static::getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->getRepository(Utilisateurs::class)->findOneBy(['prenom' => 'Malenia']);

        $nom = $user->getNom();
        $this->assertNotNull($user, 'L\'utilisateur n\'a pas été trouvé dans la base de données.');
        $this->assertEquals($nom, "HelloTher");

    }

    public function testModifNomKO(): void
    {
        $crawler = $this->client->request('GET', '/profil');

        $form = $crawler->selectButton('Sauvegarder')->form([
            'modification_profil[nom]' => ' ',
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/profil');
        $this->client->followRedirect();

        $entityManager = static::getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->getRepository(Utilisateurs::class)->findOneBy(['prenom' => 'Malenia']);

        $nom = $user->getNom();
        $this->assertNotNull($user, 'L\'utilisateur n\'a pas été trouvé dans la base de données.');
        $this->assertEquals($nom, "Déesse de la putréfaction");

    }

    public function testModifPrenomKO(): void
    {
        $crawler = $this->client->request('GET', '/profil');

        $form = $crawler->selectButton('Sauvegarder')->form([
            'modification_profil[prenom]' => ' ',
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/profil');
        $this->client->followRedirect();

        $entityManager = static::getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->getRepository(Utilisateurs::class)->findOneBy(['prenom' => 'Malenia']);

        $nom = $user->getPrenom();
        $this->assertNotNull($user, 'L\'utilisateur n\'a pas été trouvé dans la base de données.');
        $this->assertEquals($nom, "Malenia");
    }
}
