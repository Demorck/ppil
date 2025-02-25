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
        // Création et connexion de l'utilisateur
        $user = $this->userHelper->createLocataire("test@email.com");
        $this->userHelper->login($user);

        // Création d'un véhicule nécessaire pour l'offre
        $vehicule = new Vehicules();
        $vehicule->setMarque('Peugeot');
        $vehicule->setModele('208');
        $vehicule->setImmatriculation('XX-123-YY');
        $vehicule->setAnnee(new \DateTime('2025-01-01'));
        $vehicule->setNombrePlace(5);
        $vehicule->setTypeCarburant('Essence');
        $vehicule->setKilometrage(100000);

        $this->entityManager->persist($vehicule);
        $this->entityManager->flush();

        // Accéder à la page de création d'offre
        $crawler = $this->client->request('GET', '/offre/creer');

        // Vérifier que la page s'affiche correctement
        $this->assertResponseIsSuccessful();

        // Remplir et soumettre le formulaire
        $form = $crawler->selectButton('Créer l\'offre')->form([
            'formulaire_creer_offre[titre]' => 'Offre de test',
            'formulaire_creer_offre[description]' => 'Ceci est une description de test.',
            'formulaire_creer_offre[prix]' => 100,
            'formulaire_creer_offre[dateDebut]' => '2025-03-01',
            'formulaire_creer_offre[dateFin]' => '2025-03-10',
            'formulaire_creer_offre[vehicule]' => $vehicule->getId(), // Sélection du véhicule
        ]);

        $this->client->submit($form);

        // Vérifier la redirection après soumission
        $this->assertResponseRedirects('/vehicules');
        $this->client->followRedirect();

        // Vérifier que l'offre apparaît sur la page des véhicules
        $this->assertSelectorTextContains('html', 'Offre de test');

        // Vérifier en base de données que l'offre est bien enregistrée
        $offre = $this->entityManager->getRepository(Offres::class)->findOneBy(['titre' => 'Offre de test']);
        $this->assertNotNull($offre, "L'offre n'a pas été trouvée dans la base de données");
        $this->assertEquals('Offre de test', $offre->getTitre());
        $this->assertEquals('Ceci est une description de test.', $offre->getDescription());
        $this->assertEquals(100, $offre->getPrix());
        $this->assertEquals(new \DateTime('2025-03-01'), $offre->getDateDebut());
        $this->assertEquals(new \DateTime('2025-03-10'), $offre->getDateFin());
        $this->assertEquals(0, $offre->getStatut()); // Vérifier que le statut est bien mis à 0
        $this->assertEquals($vehicule->getId(), $offre->getVehicule()->getId());
    }

    public function testAccesSansConnexion(): void
    {
        // Tenter d'accéder à la page sans être connecté
        $this->client->request('GET', '/offre/creer');
        $this->assertResponseRedirects('/login');
    }
}
