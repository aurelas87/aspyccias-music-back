<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240617173454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, preview_image VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profile (name VARCHAR(9) NOT NULL, welcome VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(name)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profile_link (id INT AUTO_INCREMENT NOT NULL, type SMALLINT NOT NULL, name VARCHAR(45) NOT NULL, link VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `release` (id INT AUTO_INCREMENT NOT NULL, type SMALLINT NOT NULL, title VARCHAR(255) NOT NULL, release_date DATETIME NOT NULL, description LONGTEXT DEFAULT NULL, artwork_front_image VARCHAR(255) NOT NULL, artwork_back_image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE release_credit (id INT AUTO_INCREMENT NOT NULL, release_id INT DEFAULT NULL, type SMALLINT NOT NULL, name VARCHAR(255) NOT NULL, link VARCHAR(255) DEFAULT NULL, INDEX IDX_D2231413B12A727D (release_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE release_link (id INT AUTO_INCREMENT NOT NULL, release_id INT DEFAULT NULL, type SMALLINT NOT NULL, link VARCHAR(255) DEFAULT NULL, embedded LONGTEXT DEFAULT NULL, INDEX IDX_7B5B29E6B12A727D (release_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE release_track (id INT AUTO_INCREMENT NOT NULL, release_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, position SMALLINT NOT NULL, duration SMALLINT NOT NULL, INDEX IDX_557D8AD1B12A727D (release_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE release_credit ADD CONSTRAINT FK_D2231413B12A727D FOREIGN KEY (release_id) REFERENCES `release` (id)');
        $this->addSql('ALTER TABLE release_link ADD CONSTRAINT FK_7B5B29E6B12A727D FOREIGN KEY (release_id) REFERENCES `release` (id)');
        $this->addSql('ALTER TABLE release_track ADD CONSTRAINT FK_557D8AD1B12A727D FOREIGN KEY (release_id) REFERENCES `release` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE release_credit DROP FOREIGN KEY FK_D2231413B12A727D');
        $this->addSql('ALTER TABLE release_link DROP FOREIGN KEY FK_7B5B29E6B12A727D');
        $this->addSql('ALTER TABLE release_track DROP FOREIGN KEY FK_557D8AD1B12A727D');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE profile_link');
        $this->addSql('DROP TABLE `release`');
        $this->addSql('DROP TABLE release_credit');
        $this->addSql('DROP TABLE release_link');
        $this->addSql('DROP TABLE release_track');
    }
}
