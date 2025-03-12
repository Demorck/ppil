<?php

declare(strict_types=1);

namespace App\Tests\Controllers\Offres;

use App\Entity\Locations;
use App\Tests\Helpers\OffreHelper;
use App\Tests\Helpers\UserHelper;
use App\Tests\Helpers\Utils;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LocationOffreTest extends WebTestCase
{
    private $client;
    private $userHelper;
    private $offreHelper;
    private $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->entityManager = static::getContainer()->get('doctrine')->getManager();

        $this->userHelper = new UserHelper($this->client, $this->entityManager);
        $this->offreHelper = new OffreHelper($this->client, $this->entityManager);
    }

    protected function tearDown(): void
    {
        Utils::resetDB($this->entityManager);
        parent::tearDown();
    }

    public function testAvoirOffre(): void
    {
        $utilisateur = $this->userHelper->createLocataire("alexis@sc.fr");
        $this->userHelper->login($utilisateur);
        $offre = $this->offreHelper->createOffre();

        $crawler = $this->client->request('GET', '/offre/1');
        $this->assertAnySelectorTextContains('html', 'Peugeot');
        $this->assertAnySelectorTextContains('html', '208');
        $this->assertAnySelectorTextContains('html', '150000');
        $this->assertAnySelectorTextContains('html', 'Essence');


        $now = new \DateTime();
        $debutLocation = $now->add(new \DateInterval('P2D'));

        $now = new \DateTime();
        $finLocation = $now->add(new \DateInterval('P5D'));
        $form = $crawler->selectButton('Louer')->form([
            'offre[dateDebut]' => $debutLocation->format('Y-m-d\TH:i'),
            'offre[dateFin]' => $finLocation->format('Y-m-d\TH:i'),
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/');
        $this->client->followRedirect();

        $location = $this->entityManager->getRepository(Locations::class)->findOneBy(['locataire' => $utilisateur]);

        $this->assertNotNull($location, 'La location n\'a pas été trouvé dans la base de données.');
        $this->assertEquals($debutLocation->format('d-m-Y'), $location->getDateDebut()->format('d-m-Y'));
        $this->assertEquals($finLocation->format('d-m-Y'), $location->getDateFin()->format('d-m-Y'));
    }

    public function testErreurDateDebutAvantDebutOffre(): void
    {
        $utilisateur = $this->userHelper->createLocataire("alexis@sc.fr");
        $this->userHelper->login($utilisateur);
        $offre = $this->offreHelper->createOffre();

        $crawler = $this->client->request('GET', '/offre/1');


        $now = new \DateTime();
        $debutLocation = $now->sub(new \DateInterval('P2D'));

        $now = new \DateTime();
        $finLocation = $now->add(new \DateInterval('P5D'));
        $form = $crawler->selectButton('Louer')->form([
            'offre[dateDebut]' => $debutLocation->format('Y-m-d\TH:i'),
            'offre[dateFin]' => $finLocation->format('Y-m-d\TH:i'),
        ]);
        $this->client->submit($form);
        $this->assertAnySelectorTextContains('.error', 'La date de début doit être après la date de début');
        $this->assertAnySelectorTextContains('.error', 'La date de début doit être après aujourd\'hui.');
    }

    public function testErreurOverlap(): void
    {
        $utilisateur = $this->userHelper->createLocataire("alexis@sc.fr");
        $this->userHelper->login($utilisateur);
        $offre = $this->offreHelper->createOffre();

        $crawler = $this->client->request('GET', '/offre/1');

        $location = new Locations();
        $location->setOffre($offre);
        $location->setStatut(0);
        $location->setPrix(500);
        $location->setKilometre(100);
        $location->setLocataire($utilisateur);

        $now = new \DateTime();
        $debutLocation = $now->add(new \DateInterval('P1D'));

        $now = new \DateTime();
        $finLocation = $now->add(new \DateInterval('P7D'));

        $location->setDateDebut($debutLocation);
        $location->setDateFin($finLocation);
        $this->entityManager->persist($location);
        $this->entityManager->flush();
        $form = $crawler->selectButton('Louer')->form([
            'offre[dateDebut]' => $debutLocation->format('Y-m-d\TH:i'),
            'offre[dateFin]' => $finLocation->format('Y-m-d\TH:i'),
        ]);
        $this->client->submit($form);
        $this->assertAnySelectorTextContains('.error', 'Le véhicule est déjà réservé pour cette période.');
    }

    public function testErreurDateFinAPresOffre(): void
    {
        $utilisateur = $this->userHelper->createLocataire("alexis@sc.fr");
        $this->userHelper->login($utilisateur);
        $offre = $this->offreHelper->createOffre();

        $crawler = $this->client->request('GET', '/offre/1');

        $now = new \DateTime();
        $debutLocation = $now->add(new \DateInterval('P3M'));

        $now = new \DateTime();
        $finLocation = $now->add(new \DateInterval('P2M'));
        $form = $crawler->selectButton('Louer')->form([
            'offre[dateDebut]' => $debutLocation->format('Y-m-d\TH:i'),
            'offre[dateFin]' => $finLocation->format('Y-m-d\TH:i'),
        ]);

        $this->client->submit($form);
        $this->assertAnySelectorTextContains('.error', 'La date de fin doit être avant la date de fin de l\'offre.');
        $this->assertAnySelectorTextContains('.error', 'La date de fin doit être après la date de début.');
    }
}
