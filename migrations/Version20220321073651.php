<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220321073651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE piece_jointe_adherent (id INT AUTO_INCREMENT NOT NULL, adherent_id INT NOT NULL, designation VARCHAR(255) NOT NULL, INDEX IDX_B19097AE25F06C53 (adherent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE piece_jointe_adherent ADD CONSTRAINT FK_B19097AE25F06C53 FOREIGN KEY (adherent_id) REFERENCES adherent (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE piece_jointe_adherent');
        $this->addSql('ALTER TABLE adherent CHANGE nomprenom nomprenom VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE decision CHANGE statut statut VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE detail detail LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE reunion CHANGE listemembre listemembre LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name name VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE firstname firstname VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
