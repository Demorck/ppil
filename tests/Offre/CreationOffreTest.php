<?php

namespace App\Tests\Offre;

use App\Entity\Offres;
use App\Entity\Vehicules;
use App\Tests\Helpers\UserHelper;
use App\Tests\Helpers\Utils;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreationOffreTest extends WebTestCase
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

    public function testCreerOffre(): void
    {
        $user = $this->userHelper->createLocataire("test@email.com");
        $this->userHelper->login($user);
    
        $vehicule = new Vehicules();
        $vehicule->setMarque('Peugeot');
        $vehicule->setModele('208');
        $vehicule->setImmatriculation('XX-123-YY');
        $vehicule->setAnnee(new \DateTime('2025-01-01'));
        $vehicule->setNombrePlace(5);
        $vehicule->setTypeCarburant('Essence');
        $vehicule->setKilometrage(100000);
        $vehicule->setProprietaire($user); 
    
        $this->entityManager->persist($vehicule);
        $this->entityManager->flush();
        $this->entityManager->refresh($vehicule); 
    
        $crawler = $this->client->request('GET', '/offre/creer');
        $this->assertResponseIsSuccessful();
    
        $form = $crawler->selectButton('Publier')->form([ 
            'formulaire_creer_offre[prix]' => 100,
            'formulaire_creer_offre[dateDebut]' => '2025-03-01',
            'formulaire_creer_offre[dateFin]' => '2025-03-10',
            'formulaire_creer_offre[vehicule]' => $vehicule->getId(),
        ]);
    
        $this->client->submit($form);
    
        $offre = $this->entityManager->getRepository(Offres::class)->findOneBy(['vehicule' => $vehicule->getId()]);
        $this->assertNotNull($offre, "L'offre n'a pas été trouvée en base de données");
        $this->assertEquals(100, $offre->getPrix());
        $this->assertEquals(new \DateTime('2025-03-01'), $offre->getDateDebut());
        $this->assertEquals(new \DateTime('2025-03-10'), $offre->getDateFin());
        $this->assertEquals($vehicule->getId(), $offre->getVehicule()->getId());
    }
    
    public function testAccesSansConnexion(): void
    {
        $this->client->request('GET', '/offre/creer');
        $this->assertResponseRedirects('/login');
    }
}
