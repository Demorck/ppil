<?php

// src/DataFixtures/AppFixtures.php
namespace App\Fixtures;

use App\Entity\Abonnements;
use App\Entity\Commissions;
use App\Entity\Offres;
use App\Entity\Utilisateurs;
use App\Entity\Locations;
use App\Entity\Paiements;
use App\Entity\Vehicules;
use App\Enum\OffreStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class AppFixtures extends Fixture
{
    private $vehiculesData = [
            'Renault' => ['Clio', 'Megane', 'Twingo', 'Captur'],
            'Peugeot' => ['208', '308', '5008', 'Rifter'],
            'Tesla' => ['Model S', 'Model 3', 'Model X', 'Model Y'],
            'BMW' => ['Serie 1', 'Serie 3', 'X5', 'i8'],
            'Mercedes' => ['Classe A', 'Classe C', 'GLE', 'Sprinter'],
            'Ford' => ['Focus', 'Fiesta', 'Mustang', 'Kuga'],
            'Volkswagen' => ['Golf', 'Passat', 'Polo', 'Tiguan']
        ];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $utilisateurs = [];
        $abonnements = [];
        for ($i = 0; $i < 40; $i++) {
            $user = new Utilisateurs();
            $user->setEmail($faker->email)
                ->setNom($faker->lastName)
                ->setPrenom($faker->firstName)
                ->setPassword(password_hash('password', PASSWORD_BCRYPT));

            if ($faker->boolean(80))
                $user->setStatut(1);
            else
                $user->setStatut(0);

            if ($faker->boolean(10))
                $user->setStatut(2);

            $roles = [];

            if ($faker->boolean(80)) {
                $roles[] = 'ROLE_LOCATAIRE';
            }

            if ($faker->boolean(60)) {
                $roles[] = 'ROLE_PROPRIETAIRE';
            }

            if ($faker->boolean(30)) {
                $roles[] = 'ROLE_JURISTE';
            }

            $user->setRoles($roles);


            $manager->persist($user);
            $utilisateurs[] = $user;

            if ($faker->boolean(30)) {
                $abonnement = new Abonnements();
                $abonnement->setUtilisateur($user)
                    ->setType($faker->numberBetween(0, 2));

                switch ($abonnement->getType()) {
                    case 0:
                        $abonnement->setPrix(10);
                        break;
                    case 1:
                        $abonnement->setPrix(40);
                        break;
                    case 2:
                        $abonnement->setPrix(400);
                        break;
                }


                    $abonnement
                    ->setDateDebut($faker->dateTimeBetween('-6 months', 'now'))
                    ->setDateFin($faker->dateTimeBetween('now', '+6 months'))
                    ->setStatut($faker->numberBetween(0, 2));

                $manager->persist($abonnement);
                $abonnements[] = $abonnement;
            }
        }

        $vehicules = [];
        for ($i = 0; $i < 100; $i++)
        {
            $vehicule = new Vehicules();
            $proprio = array_filter($utilisateurs, function ($utilisateur) {
                return in_array('ROLE_PROPRIETAIRE', $utilisateur->getRoles());
            });


            $marque = $faker->randomElement(array_keys($this->vehiculesData));
            $modele = $faker->randomElement($this->vehiculesData[$marque]);

            $imageUrl = "https://picsum.photos/640/480";

            $vehicule->setProprietaire($faker->randomElement($proprio))
                ->setAnnee($faker->dateTimeBetween('-10 years', 'now'))
                ->setImmatriculation($faker->regexify('[A-Z]{2}-[0-9]{3}-[A-Z]{2}'))
                ->setMarque($marque)
                ->setModele($modele)
                ->setKilometrage($faker->numberBetween(0, 200000))
                ->setNombrePlace($faker->numberBetween(2, 9))
                ->setTitre($faker->sentence())
                ->setImageName($imageUrl)
                ->setTypeCarburant($faker->randomElement(['Essence', 'Diesel', 'Electrique', 'Hybride']));

            $manager->persist($vehicule);
            $vehicules[] = $vehicule;
        }

        // Générer des offres, dont certaines expirées
        $offres = [];
        for ($i = 0; $i < 50; $i++) {
            $offre = new Offres();
            $offre->setPrix($faker->randomFloat(2, 5, 200))
                ->setDateDebut($faker->dateTimeBetween('-6 months', 'now'))
                ->setDateFin($faker->dateTimeBetween('now', '+6 months'))
                ->setStatut($faker->numberBetween(0, 3))
                ->setVehicule($faker->randomElement($vehicules));

            $manager->persist($offre);
            $offres[] = $offre;
        }

        $locations = [];
        for ($i = 0; $i < 30; $i++) {
            $location = new Locations();
            $locataire = array_filter($utilisateurs, function ($utilisateur) {
                return in_array('ROLE_LOCATAIRE', $utilisateur->getRoles());
            });
            $location->setOffre($faker->randomElement($offres))
                ->setLocataire($faker->randomElement($locataire))
                ->setDateDebut($faker->dateTimeBetween('-3 months', 'now'))
                ->setDateFin($faker->dateTimeBetween('now', '+3 months'))
                ->setStatut($faker->numberBetween(0, 3))
                ->setPrix($faker->randomFloat(2, 10, 500));

            $manager->persist($location);
            $locations[] = $location;
        }

        for ($i = 0; $i < 30; $i++) {
            $paiement = new Paiements();
            $montant = $faker->randomFloat(2, 10, 500);
            $commission = new Commissions();
            $commission->setMontant($montant * 0.15)
                ->setPaiement($paiement)
                ->setPourcentage(15);

            if ($faker->boolean())
            {
                $a = $faker->randomElement($locations);
                $locations = array_filter($locations, static function ($element) use ($a) {
                    return $element !== $a;
                });
                $paiement->setLocation($a);
            } else {
                $a = $faker->randomElement($abonnements);
                $abonnements = array_filter($abonnements, static function ($element) use ($a) {
                    return $element !== $a;
                });
                $paiement->setAbonnementId($a);
            }

            $paiement->setMontant($montant)
                ->setCommissions($commission)
                ->setDate($faker->dateTimeBetween('-3 months', 'now'))
                ->setStatut($faker->numberBetween(0, 3));

            $manager->persist($paiement);
        }

        $manager->flush();
    }
}
