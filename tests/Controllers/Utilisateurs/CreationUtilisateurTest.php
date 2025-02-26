<?php

declare(strict_types=1);

namespace App\Tests\Controllers\Utilisateurs;

use App\Entity\Utilisateurs;
use App\Tests\Helpers\UserHelper;
use App\Tests\Helpers\Utils;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use function Symfony\Component\Translation\t;

class CreationUtilisateurTest extends WebTestCase
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

    public function testCreateUtilisateur(): void
    {
        $crawler = $this->client->request('GET', '/register');

        $form = $crawler->selectButton('S\'inscrire')->form([
            'registration_form[email]' => 'test@email.com',
            'registration_form[plainPassword]' => '!c5x8qbD*IT@ZY9R8*3ljBey*gZv5FwZS7xd#@@htj4OC#ywRANjUjzO5wHSYzj7^%U3VWICZ&2VHHfLj7J$RG$oNFPZDRZpyu^#52P%aelZp%SLKaV#1JvSFBdJyz6B',
            'registration_form[nom]' => 'O-Moriah',
            'registration_form[prenom]' => 'Magdalene',
        ]);

        $form['registration_form[agreeTerms]']->tick();

        $this->client->submit($form);
        $this->assertResponseRedirects('/');
        $this->client->followRedirect();

        $entityManager = static::getContainer()->get('doctrine.orm.entity_manager');
        $user =  $entityManager->getRepository(Utilisateurs::class)->findOneBy(['nom' => 'O-Moriah']);

        $this->assertNotNull($user, 'L\'utilisateur n\'a pas été trouvé dans la base de données.');
        $this->assertEquals('test@email.com', $user->getEmail());
        $this->assertEquals('Magdalene', $user->getPrenom());
        $this->assertNotNull($user->getPassword());
    }

    public function testPassword(): void
    {
        $crawler = $this->client->request('GET', '/register');

        $form = $crawler->selectButton('S\'inscrire')->form([
            'registration_form[email]' => 'test@email.com',
            'registration_form[nom]' => 'O-Moriah',
            'registration_form[prenom]' => 'Magdalene',
        ]);

        $form['registration_form[agreeTerms]']->tick();
        $form['registration_form[plainPassword]'] = 'abc';
        $this->client->submit($form);
        $this->assertAnySelectorTextContains('.error', 'au moins 6 caractères.');



        $form['registration_form[plainPassword]'] = 'p@ssw0rd123';
        $this->client->submit($form);
        $this->assertAnySelectorTextContains('.error', 'Le mot de passe est trop facile, veuillez mettre un mot de passe plus compliqué.');
    }

    public function testAgreeTerms(): void
    {
        $crawler = $this->client->request('GET', '/register');

        $form = $crawler->selectButton('S\'inscrire')->form([
            'registration_form[email]' => 'test@email.com',
            'registration_form[plainPassword]' => '!c5x8qbD*IT@ZY9R8*3ljBey*gZv5FwZS7xd#@@htj4OC#ywRANjUjzO5wHSYzj7^%U3VWICZ&2VHHfLj7J$RG$oNFPZDRZpyu^#52P%aelZp%SLKaV#1JvSFBdJyz6B',
            'registration_form[nom]' => 'O-Moriah',
            'registration_form[prenom]' => 'Magdalene',
        ]);

        $this->client->submit($form);
        $this->assertAnySelectorTextContains('.error', 'Vous devez accepter les conditions pour continuer.');
    }

    public function testUserExists(): void
    {
        $crawler = $this->client->request('GET', '/register');

        $form = $crawler->selectButton('S\'inscrire')->form([
            'registration_form[email]' => 'test@email.com',
            'registration_form[plainPassword]' => '!c5x8qbD*IT@ZY9R8*3ljBey*gZv5FwZS7xd#@@htj4OC#ywRANjUjzO5wHSYzj7^%U3VWICZ&2VHHfLj7J$RG$oNFPZDRZpyu^#52P%aelZp%SLKaV#1JvSFBdJyz6B',
            'registration_form[nom]' => 'O-Moriah',
            'registration_form[prenom]' => 'Magdalene',
        ]);

        $form['registration_form[agreeTerms]']->tick();
        $this->client->submit($form);

        $crawler = $this->client->request('GET', '/register');

        $form = $crawler->selectButton('S\'inscrire')->form([
            'registration_form[email]' => 'test@email.com',
            'registration_form[plainPassword]' => '!c5x8qbD*IT@ZY9R8*3ljBey*gZv5FwZS7xd#@@htj4OC#ywRANjUjzO5wHSYzj7^%U3VWICZ&2VHHfLj7J$RG$oNFPZDRZpyu^#52P%aelZp%SLKaV#1JvSFBdJyz6B',
            'registration_form[nom]' => 'O-Moriah',
            'registration_form[prenom]' => 'Magdalene',
        ]);

        $form['registration_form[agreeTerms]']->tick();

        $this->client->submit($form);
        $this->assertAnySelectorTextContains('.error', 'Un compte existe déjà avec cet email');
    }
}
