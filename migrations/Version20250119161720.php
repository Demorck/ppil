<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250119161720 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnements (id INT AUTO_INCREMENT NOT NULL, locataire_id INT DEFAULT NULL, type INT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, statut INT NOT NULL, UNIQUE INDEX UNIQ_4788B767D8A38199 (locataire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE administrateurs (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commissions (id INT AUTO_INCREMENT NOT NULL, paiement_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, pourcentage DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_7EA273CC2A4C4478 (paiement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE juriste (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE litiges (id INT AUTO_INCREMENT NOT NULL, location_id INT NOT NULL, juriste_id INT NOT NULL, description VARCHAR(255) NOT NULL, statut INT NOT NULL, UNIQUE INDEX UNIQ_28E33B3A64D218E (location_id), INDEX IDX_28E33B3A7B3CC7FD (juriste_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE locataires (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE locations (id INT AUTO_INCREMENT NOT NULL, offre_id INT NOT NULL, locataire_id INT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, prix DOUBLE PRECISION NOT NULL, statut INT NOT NULL, kilometre INT DEFAULT NULL, INDEX IDX_17E64ABA4CC8505A (offre_id), INDEX IDX_17E64ABAD8A38199 (locataire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offres (id INT AUTO_INCREMENT NOT NULL, vehicule_id INT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, prix DOUBLE PRECISION NOT NULL, statut INT NOT NULL, INDEX IDX_C6AC35444A4A3511 (vehicule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paiements (id INT AUTO_INCREMENT NOT NULL, location_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, date DATE NOT NULL, statut INT NOT NULL, UNIQUE INDEX UNIQ_E1B02E1264D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proprietaires (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateurs (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, statut INT NOT NULL, discr VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicules (id INT AUTO_INCREMENT NOT NULL, proprietaire_id INT NOT NULL, marque VARCHAR(255) NOT NULL, modele VARCHAR(255) NOT NULL, immatriculation VARCHAR(255) NOT NULL, annee INT NOT NULL, nombre_place INT NOT NULL, type_carburant VARCHAR(255) NOT NULL, kilometrage INT NOT NULL, INDEX IDX_78218C2D76C50E4A (proprietaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE abonnements ADD CONSTRAINT FK_4788B767D8A38199 FOREIGN KEY (locataire_id) REFERENCES locataires (id)');
        $this->addSql('ALTER TABLE administrateurs ADD CONSTRAINT FK_B5ED4E13BF396750 FOREIGN KEY (id) REFERENCES utilisateurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commissions ADD CONSTRAINT FK_7EA273CC2A4C4478 FOREIGN KEY (paiement_id) REFERENCES paiements (id)');
        $this->addSql('ALTER TABLE juriste ADD CONSTRAINT FK_C45A4ADBF396750 FOREIGN KEY (id) REFERENCES utilisateurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE litiges ADD CONSTRAINT FK_28E33B3A64D218E FOREIGN KEY (location_id) REFERENCES locations (id)');
        $this->addSql('ALTER TABLE litiges ADD CONSTRAINT FK_28E33B3A7B3CC7FD FOREIGN KEY (juriste_id) REFERENCES juriste (id)');
        $this->addSql('ALTER TABLE locataires ADD CONSTRAINT FK_2C12880DBF396750 FOREIGN KEY (id) REFERENCES utilisateurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE locations ADD CONSTRAINT FK_17E64ABA4CC8505A FOREIGN KEY (offre_id) REFERENCES offres (id)');
        $this->addSql('ALTER TABLE locations ADD CONSTRAINT FK_17E64ABAD8A38199 FOREIGN KEY (locataire_id) REFERENCES locataires (id)');
        $this->addSql('ALTER TABLE offres ADD CONSTRAINT FK_C6AC35444A4A3511 FOREIGN KEY (vehicule_id) REFERENCES vehicules (id)');
        $this->addSql('ALTER TABLE paiements ADD CONSTRAINT FK_E1B02E1264D218E FOREIGN KEY (location_id) REFERENCES locations (id)');
        $this->addSql('ALTER TABLE proprietaires ADD CONSTRAINT FK_74D75B73BF396750 FOREIGN KEY (id) REFERENCES utilisateurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vehicules ADD CONSTRAINT FK_78218C2D76C50E4A FOREIGN KEY (proprietaire_id) REFERENCES proprietaires (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnements DROP FOREIGN KEY FK_4788B767D8A38199');
        $this->addSql('ALTER TABLE administrateurs DROP FOREIGN KEY FK_B5ED4E13BF396750');
        $this->addSql('ALTER TABLE commissions DROP FOREIGN KEY FK_7EA273CC2A4C4478');
        $this->addSql('ALTER TABLE juriste DROP FOREIGN KEY FK_C45A4ADBF396750');
        $this->addSql('ALTER TABLE litiges DROP FOREIGN KEY FK_28E33B3A64D218E');
        $this->addSql('ALTER TABLE litiges DROP FOREIGN KEY FK_28E33B3A7B3CC7FD');
        $this->addSql('ALTER TABLE locataires DROP FOREIGN KEY FK_2C12880DBF396750');
        $this->addSql('ALTER TABLE locations DROP FOREIGN KEY FK_17E64ABA4CC8505A');
        $this->addSql('ALTER TABLE locations DROP FOREIGN KEY FK_17E64ABAD8A38199');
        $this->addSql('ALTER TABLE offres DROP FOREIGN KEY FK_C6AC35444A4A3511');
        $this->addSql('ALTER TABLE paiements DROP FOREIGN KEY FK_E1B02E1264D218E');
        $this->addSql('ALTER TABLE proprietaires DROP FOREIGN KEY FK_74D75B73BF396750');
        $this->addSql('ALTER TABLE vehicules DROP FOREIGN KEY FK_78218C2D76C50E4A');
        $this->addSql('DROP TABLE abonnements');
        $this->addSql('DROP TABLE administrateurs');
        $this->addSql('DROP TABLE commissions');
        $this->addSql('DROP TABLE juriste');
        $this->addSql('DROP TABLE litiges');
        $this->addSql('DROP TABLE locataires');
        $this->addSql('DROP TABLE locations');
        $this->addSql('DROP TABLE offres');
        $this->addSql('DROP TABLE paiements');
        $this->addSql('DROP TABLE proprietaires');
        $this->addSql('DROP TABLE utilisateurs');
        $this->addSql('DROP TABLE vehicules');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
