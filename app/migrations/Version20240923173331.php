<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240923173331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE loan (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, interest_rate INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE car CHANGE model_id model_id INT DEFAULT NULL, CHANGE brand_id brand_id INT DEFAULT NULL');
        $this->addSql("
        INSERT INTO app.loan (title,interest_rate) VALUES
        ('program 1',10),
        ('program 2',15),
        ('program 3',20);
     ");

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE loan');
        $this->addSql('ALTER TABLE car CHANGE model_id model_id INT NOT NULL, CHANGE brand_id brand_id INT NOT NULL');
    }
}
