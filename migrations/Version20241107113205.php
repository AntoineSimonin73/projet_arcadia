<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241107113205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nourrissage ADD rapport_veterinaire_id INT NOT NULL');
        $this->addSql('ALTER TABLE nourrissage ADD CONSTRAINT FK_D00D800582A908C2 FOREIGN KEY (rapport_veterinaire_id) REFERENCES rapport_veterinaire (id)');
        $this->addSql('CREATE INDEX IDX_D00D800582A908C2 ON nourrissage (rapport_veterinaire_id)');
        $this->addSql('ALTER TABLE rapport_veterinaire CHANGE nourriture nourriture_proposee VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nourrissage DROP FOREIGN KEY FK_D00D800582A908C2');
        $this->addSql('DROP INDEX IDX_D00D800582A908C2 ON nourrissage');
        $this->addSql('ALTER TABLE nourrissage DROP rapport_veterinaire_id');
        $this->addSql('ALTER TABLE rapport_veterinaire CHANGE nourriture_proposee nourriture VARCHAR(255) NOT NULL');
    }
}
