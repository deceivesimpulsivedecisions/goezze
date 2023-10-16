<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231015185229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE package_itenary ADD package_id INT NOT NULL');
        $this->addSql('ALTER TABLE package_itenary ADD CONSTRAINT FK_6B44F7E5F44CABFF FOREIGN KEY (package_id) REFERENCES package (id)');
        $this->addSql('CREATE INDEX IDX_6B44F7E5F44CABFF ON package_itenary (package_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE package_itenary DROP FOREIGN KEY FK_6B44F7E5F44CABFF');
        $this->addSql('DROP INDEX IDX_6B44F7E5F44CABFF ON package_itenary');
        $this->addSql('ALTER TABLE package_itenary DROP package_id');
    }
}
