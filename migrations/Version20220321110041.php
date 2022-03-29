<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220321110041 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, name VARCHAR(100) NOT NULL, firstname VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adherent ADD numero NUMERIC(10, 0) NOT NULL, ADD cin NUMERIC(10, 0) NOT NULL, ADD nomconjoint VARCHAR(255) NOT NULL, ADD cinconjoint VARCHAR(255) NOT NULL, ADD adresse VARCHAR(255) NOT NULL, ADD telephone NUMERIC(10, 0) NOT NULL, ADD etatcivil VARCHAR(255) NOT NULL, ADD nombrefamille VARCHAR(255) NOT NULL, ADD logement VARCHAR(255) NOT NULL, ADD prixlocation NUMERIC(10, 0) NOT NULL, ADD nombrechambre NUMERIC(10, 0) NOT NULL, ADD electricite TINYINT(1) NOT NULL, ADD eau VARCHAR(255) NOT NULL, ADD installationnondisponible VARCHAR(255) NOT NULL, ADD handicap TINYINT(1) NOT NULL, ADD typehandicap VARCHAR(255) NOT NULL, ADD famillehandicap TINYINT(1) NOT NULL, ADD maladiechronique TINYINT(1) NOT NULL, ADD typemaladiechronique VARCHAR(255) NOT NULL, ADD montantrevenu NUMERIC(10, 0) NOT NULL, ADD source VARCHAR(255) NOT NULL, ADD resume VARCHAR(255) NOT NULL, ADD demande VARCHAR(255) NOT NULL, ADD quienregistrefichier VARCHAR(255) NOT NULL, ADD date DATE NOT NULL, ADD datearchivage DATE NOT NULL, ADD statut VARCHAR(255) DEFAULT NULL, CHANGE nomprenom nom VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE adherent ADD nomprenom VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP numero, DROP nom, DROP cin, DROP nomconjoint, DROP cinconjoint, DROP adresse, DROP telephone, DROP etatcivil, DROP nombrefamille, DROP logement, DROP prixlocation, DROP nombrechambre, DROP electricite, DROP eau, DROP installationnondisponible, DROP handicap, DROP typehandicap, DROP famillehandicap, DROP maladiechronique, DROP typemaladiechronique, DROP montantrevenu, DROP source, DROP resume, DROP demande, DROP quienregistrefichier, DROP date, DROP datearchivage, DROP statut');
        $this->addSql('ALTER TABLE decision CHANGE statut statut VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE detail detail LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE piece_jointe_adherent CHANGE designation designation VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE reunion CHANGE listemembre listemembre LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
