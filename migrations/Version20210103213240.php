<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210103213240 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE apprenant_profil_sorties (apprenant_id INT NOT NULL, profil_sorties_id INT NOT NULL, INDEX IDX_D7D3C81FC5697D6D (apprenant_id), INDEX IDX_D7D3C81F2E19944C (profil_sorties_id), PRIMARY KEY(apprenant_id, profil_sorties_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE apprenant_profil_sorties ADD CONSTRAINT FK_D7D3C81FC5697D6D FOREIGN KEY (apprenant_id) REFERENCES apprenant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE apprenant_profil_sorties ADD CONSTRAINT FK_D7D3C81F2E19944C FOREIGN KEY (profil_sorties_id) REFERENCES profil_sorties (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE avatar avatar LONGBLOB NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE apprenant_profil_sorties');
        $this->addSql('ALTER TABLE user CHANGE avatar avatar LONGBLOB DEFAULT NULL');
    }
}
