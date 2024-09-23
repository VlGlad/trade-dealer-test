<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240923174639 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE loan_request (id INT AUTO_INCREMENT NOT NULL, car_id INT NOT NULL, loan_id INT NOT NULL, initial_payment DOUBLE PRECISION NOT NULL, loan_term INT NOT NULL, INDEX IDX_15D801EBC3C6F69F (car_id), INDEX IDX_15D801EBCE73868F (loan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE loan_request ADD CONSTRAINT FK_15D801EBC3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
        $this->addSql('ALTER TABLE loan_request ADD CONSTRAINT FK_15D801EBCE73868F FOREIGN KEY (loan_id) REFERENCES loan (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE loan_request DROP FOREIGN KEY FK_15D801EBC3C6F69F');
        $this->addSql('ALTER TABLE loan_request DROP FOREIGN KEY FK_15D801EBCE73868F');
        $this->addSql('DROP TABLE loan_request');
    }
}
