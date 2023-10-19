<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231019060706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE package ADD type_id INT NOT NULL');
        $this->addSql('ALTER TABLE package ADD CONSTRAINT FK_DE686795C54C8C93 FOREIGN KEY (type_id) REFERENCES package_type (id)');
        $this->addSql('CREATE INDEX IDX_DE686795C54C8C93 ON package (type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE package DROP FOREIGN KEY FK_DE686795C54C8C93');
        $this->addSql('DROP INDEX IDX_DE686795C54C8C93 ON package');
        $this->addSql('ALTER TABLE package DROP type_id');
    }
}
