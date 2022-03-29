<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220310075057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adherent (id INT AUTO_INCREMENT NOT NULL, numero NUMERIC(10, 0) NOT NULL, nom VARCHAR(255) NOT NULL, cin NUMERIC(10, 0) NOT NULL, nomconjoint VARCHAR(255) NOT NULL, cinconjoint VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, telephone NUMERIC(10, 0) NOT NULL, etatcivil VARCHAR(255) NOT NULL, nombrefamille VARCHAR(255) NOT NULL, logement TINYINT(1) NOT NULL, prixlocation NUMERIC(10, 0) NOT NULL, nombrechambre NUMERIC(10, 0) NOT NULL, electricite TINYINT(1) NOT NULL, eau VARCHAR(255) NOT NULL, installationnondisponible VARCHAR(255) NOT NULL, handicap TINYINT(1) NOT NULL, typehandicap VARCHAR(255) NOT NULL, famillehandicap TINYINT(1) NOT NULL, maladiechronique TINYINT(1) NOT NULL, typemaladiechronique VARCHAR(255) NOT NULL, montantrevenu NUMERIC(10, 0) NOT NULL, source VARCHAR(255) NOT NULL, resume VARCHAR(255) NOT NULL, demande VARCHAR(255) NOT NULL, quienregistrefichier VARCHAR(255) NOT NULL, date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reunion (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, listemembre LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE adherent');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name name VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE firstname firstname VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
