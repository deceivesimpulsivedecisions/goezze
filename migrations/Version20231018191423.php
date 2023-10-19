<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231018191423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE package_destination (package_id INT NOT NULL, destination_id INT NOT NULL, INDEX IDX_F31D24CCF44CABFF (package_id), INDEX IDX_F31D24CC816C6140 (destination_id), PRIMARY KEY(package_id, destination_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE package_destination ADD CONSTRAINT FK_F31D24CCF44CABFF FOREIGN KEY (package_id) REFERENCES package (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE package_destination ADD CONSTRAINT FK_F31D24CC816C6140 FOREIGN KEY (destination_id) REFERENCES destination (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE package_destination DROP FOREIGN KEY FK_F31D24CCF44CABFF');
        $this->addSql('ALTER TABLE package_destination DROP FOREIGN KEY FK_F31D24CC816C6140');
        $this->addSql('DROP TABLE package_destination');
    }
}
