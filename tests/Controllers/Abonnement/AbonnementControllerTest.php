<?php

namespace App\Tests\Controllers\Abonnement;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use App\Entity\Abonnements;
use App\Entity\Locations;

use App\Tests\Helpers\UserHelper;
use App\Tests\Helpers\Utils;

use DateTime;

class AbonnementControllerTest extends WebTestCase
{
    private $client;
    private $entityManager;
    private $userHelper;

    protected function setUp():void {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();
        $this->userHelper = new UserHelper($this->client, $this->entityManager);
    }

    protected function tearDown():void{
        Utils::resetDB($this->entityManager);
        parent::tearDown();
    }

    public function testListeAbonnement(): void
    {
        
        $user = $this->userHelper->createLocataire("a@a.a");
        $this->userHelper->login($user);
        
        $abonnement = new Abonnements();
        $abonnement->setUtilisateur($user);
        $abonnement->setType(1);
        $abonnement->setDateDebut(new DateTime("2025-01-01"));
        $abonnement->setDateFin(new DateTime("9999-01-01"));
        $abonnement->setStatut(1);

        $this->entityManager->persist($abonnement);
        $this->entityManager->flush();
        
        $crawler = $this->client->request("GET", "/abonnement");

        $this->assertEquals(1, $crawler->filter(".abonnement")->count());
    }

    public function testListeOffres(): void
    {
        
        $user = $this->userHelper->createLocataire("a@a.a");
        $this->userHelper->login($user);
        
        $abonnement = new Abonnements();
        $abonnement->setUtilisateur($user);
        $abonnement->setType(1);
        $abonnement->setDateDebut(new DateTime("2025-01-01"));
        $abonnement->setDateFin(new DateTime("9999-01-01"));
        $abonnement->setStatut(1);

        $this->entityManager->persist($abonnement);
        $this->entityManager->flush();
        
        $crawler = $this->client->request("GET", "/abonnement");

        $this->assertEquals(1, $crawler->filter(".offre")->count());
    }

}
