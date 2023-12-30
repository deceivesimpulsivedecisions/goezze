<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231229175520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE package_enquiry ADD package_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE package_enquiry ADD CONSTRAINT FK_12C20C08F44CABFF FOREIGN KEY (package_id) REFERENCES package (id)');
        $this->addSql('CREATE INDEX IDX_12C20C08F44CABFF ON package_enquiry (package_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE package_enquiry DROP FOREIGN KEY FK_12C20C08F44CABFF');
        $this->addSql('DROP INDEX IDX_12C20C08F44CABFF ON package_enquiry');
        $this->addSql('ALTER TABLE package_enquiry DROP package_id');
    }
}
