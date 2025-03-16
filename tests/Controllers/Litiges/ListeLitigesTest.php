<?php

declare(strict_types=1);

namespace App\Tests\Controllers\Litiges;

use App\Entity\Litiges;
use App\Entity\Locations;
use App\Tests\Helpers\OffreHelper;
use App\Tests\Helpers\UserHelper;
use App\Tests\Helpers\Utils;
use phpDocumentor\Reflection\Location;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ListeLitigesTest extends WebTestCase
{
    private $client;
    private $userHelper;
    private $entityManager;

    private $offreHelper;

    private $user;

    private $jurist;

    private $litige;

    private $offre;

    private $location;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->entityManager = static::getContainer()->get('doctrine')->getManager();

        $this->userHelper = new UserHelper($this->client, $this->entityManager);

        $this->user = $this->userHelper->createLocataire("waaazaaaaa@trouduc.fr69");

        $this->jurist = $this->userHelper->createLocataire("jurist@jur.fr");

        $this->offreHelper = new OffreHelper($this->client, $this->entityManager);

        $this->offre = $this->offreHelper->createOffre();


        $this->location = new Locations();
        $this->location->setLocataire($this->user);
        $this->location->setOffre($this->offre);
        $this->location->setDateDebut(new \DateTime('now'));
        $this->location->setDateFin(new \DateTime('+1 month'));
        $this->location->setStatut(0);
        $this->location->setPrix(12);

        $this->entityManager->persist($this->location);
        $this->entityManager->flush();

        $this->litige = new Litiges();
        $this->litige->setDescription("Litige de test");
        $this->litige->setStatut(0);
        $this->litige->setLocation($this->location);
        $this->litige->setJuriste($this->jurist);

        $this->entityManager->persist($this->litige);
        $this->entityManager->flush();
    }

    protected function tearDown(): void
    {
        Utils::resetDB($this->entityManager);
        parent::tearDown();
    }

    public function testRecuperationLitiges()
    {
        $crawler = $this->client->request('GET', '/');

        $this->userHelper->login($this->user);
        $this->assertResponseIsSuccessful();


        $crawler = $this->client->request('GET', '/litiges');

        // Check that the response is successful
        $this->assertResponseIsSuccessful();

        // Check that the litige is present in the response
        $this->assertSelectorTextContains('.container .flex.flex-row.items-center .flex-1.px-2', 'non traité');
    }

    public function testRecuperationLitigesSansConnexion()
    {
        $crawler = $this->client->request('GET', '/litiges');

        // Check that the response is successful
        $this->assertResponseRedirects('/login');
    }
}
