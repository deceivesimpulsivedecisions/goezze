<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231229152339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE package_enquiry (id INT AUTO_INCREMENT NOT NULL, from_date DATE NOT NULL, adults INT NOT NULL, childrens INT NOT NULL, infants INT NOT NULL, amount DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE package ADD package_enquiry_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE package ADD CONSTRAINT FK_DE686795B18D780A FOREIGN KEY (package_enquiry_id) REFERENCES package_enquiry (id)');
        $this->addSql('CREATE INDEX IDX_DE686795B18D780A ON package (package_enquiry_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE package DROP FOREIGN KEY FK_DE686795B18D780A');
        $this->addSql('DROP TABLE package_enquiry');
        $this->addSql('DROP INDEX IDX_DE686795B18D780A ON package');
        $this->addSql('ALTER TABLE package DROP package_enquiry_id');
    }
}
