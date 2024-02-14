<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240214130618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE word_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE word (id INT NOT NULL, value TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE embedding ADD word_id INT NOT NULL');
        $this->addSql('ALTER TABLE embedding ADD CONSTRAINT FK_485B55AEE357438D FOREIGN KEY (word_id) REFERENCES word (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_485B55AEE357438D ON embedding (word_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE embedding DROP CONSTRAINT FK_485B55AEE357438D');
        $this->addSql('DROP SEQUENCE word_id_seq CASCADE');
        $this->addSql('DROP TABLE word');
        $this->addSql('DROP INDEX IDX_485B55AEE357438D');
        $this->addSql('ALTER TABLE embedding DROP word_id');
    }
}
