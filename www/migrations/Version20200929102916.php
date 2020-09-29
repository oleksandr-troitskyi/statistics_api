<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200929102916 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE aggregated_score (id INT AUTO_INCREMENT NOT NULL, hotel_id INT NOT NULL, created_date DATE NOT NULL, score DOUBLE PRECISION NOT NULL, reviews INT NOT NULL, INDEX IDX_25F028EF3243BB18 (hotel_id), INDEX hotelid_date (hotel_id, created_date), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE hotel (id INT AUTO_INCREMENT NOT NULL, name LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, hotel_id INT NOT NULL, score DOUBLE PRECISION NOT NULL, comment LONGTEXT NOT NULL, created_date DATETIME NOT NULL, INDEX IDX_794381C63243BB18 (hotel_id), INDEX id_date (hotel_id, created_date), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE aggregated_score ADD CONSTRAINT FK_25F028EF3243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C63243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE aggregated_score DROP FOREIGN KEY FK_25F028EF3243BB18');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C63243BB18');
        $this->addSql('DROP TABLE aggregated_score');
        $this->addSql('DROP TABLE hotel');
        $this->addSql('DROP TABLE review');
    }
}
