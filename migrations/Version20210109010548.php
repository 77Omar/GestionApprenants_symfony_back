<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210109010548 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE competences DROP FOREIGN KEY FK_DB2077CEB3E9C81');
        $this->addSql('DROP INDEX IDX_DB2077CEB3E9C81 ON competences');
        $this->addSql('ALTER TABLE competences DROP niveau_id');
        $this->addSql('ALTER TABLE niveau ADD competence_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE niveau ADD CONSTRAINT FK_4BDFF36B15761DAB FOREIGN KEY (competence_id) REFERENCES competences (id)');
        $this->addSql('CREATE INDEX IDX_4BDFF36B15761DAB ON niveau (competence_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE competences ADD niveau_id INT NOT NULL');
        $this->addSql('ALTER TABLE competences ADD CONSTRAINT FK_DB2077CEB3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_DB2077CEB3E9C81 ON competences (niveau_id)');
        $this->addSql('ALTER TABLE niveau DROP FOREIGN KEY FK_4BDFF36B15761DAB');
        $this->addSql('DROP INDEX IDX_4BDFF36B15761DAB ON niveau');
        $this->addSql('ALTER TABLE niveau DROP competence_id');
    }
}
