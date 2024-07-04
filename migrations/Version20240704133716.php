<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240704133716 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, date DATETIME NOT NULL, preview_image VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE news_translation (locale VARCHAR(2) NOT NULL, news_id INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_9D5CF320B5A459A0 (news_id), PRIMARY KEY(news_id, locale)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profile (locale VARCHAR(2) NOT NULL, welcome VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(locale)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profile_link (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, link VARCHAR(255) NOT NULL, position SMALLINT NOT NULL, UNIQUE INDEX UNIQ_806416655E237E06 (name), UNIQUE INDEX UNIQ_80641665462CE4F5 (position), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `release` (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, type SMALLINT NOT NULL, release_date DATETIME NOT NULL, title VARCHAR(255) NOT NULL, artwork_front_image VARCHAR(255) NOT NULL, artwork_back_image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE release_credit (release_id INT NOT NULL, release_credit_type_id INT NOT NULL, full_name VARCHAR(255) NOT NULL, link VARCHAR(255) DEFAULT NULL, INDEX IDX_D2231413B12A727D (release_id), INDEX IDX_D2231413A2ACB85A (release_credit_type_id), PRIMARY KEY(release_id, release_credit_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE release_credit_type (id INT AUTO_INCREMENT NOT NULL, credit_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_A0C5558C59E1C13B (credit_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE release_link (type SMALLINT NOT NULL, release_id INT NOT NULL, link VARCHAR(255) DEFAULT NULL, embedded LONGTEXT DEFAULT NULL, INDEX IDX_7B5B29E6B12A727D (release_id), PRIMARY KEY(release_id, type)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE release_track (title VARCHAR(255) NOT NULL, release_id INT NOT NULL, position SMALLINT NOT NULL, duration SMALLINT NOT NULL, INDEX IDX_557D8AD1B12A727D (release_id), PRIMARY KEY(release_id, title)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE release_translation (locale VARCHAR(2) NOT NULL, release_id INT NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_9FB0F8F7B12A727D (release_id), PRIMARY KEY(release_id, locale)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE news_translation ADD CONSTRAINT FK_9D5CF320B5A459A0 FOREIGN KEY (news_id) REFERENCES news (id)');
        $this->addSql('ALTER TABLE release_credit ADD CONSTRAINT FK_D2231413B12A727D FOREIGN KEY (release_id) REFERENCES `release` (id)');
        $this->addSql('ALTER TABLE release_credit ADD CONSTRAINT FK_D2231413A2ACB85A FOREIGN KEY (release_credit_type_id) REFERENCES release_credit_type (id)');
        $this->addSql('ALTER TABLE release_link ADD CONSTRAINT FK_7B5B29E6B12A727D FOREIGN KEY (release_id) REFERENCES `release` (id)');
        $this->addSql('ALTER TABLE release_track ADD CONSTRAINT FK_557D8AD1B12A727D FOREIGN KEY (release_id) REFERENCES `release` (id)');
        $this->addSql('ALTER TABLE release_translation ADD CONSTRAINT FK_9FB0F8F7B12A727D FOREIGN KEY (release_id) REFERENCES `release` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE news_translation DROP FOREIGN KEY FK_9D5CF320B5A459A0');
        $this->addSql('ALTER TABLE release_credit DROP FOREIGN KEY FK_D2231413B12A727D');
        $this->addSql('ALTER TABLE release_credit DROP FOREIGN KEY FK_D2231413A2ACB85A');
        $this->addSql('ALTER TABLE release_link DROP FOREIGN KEY FK_7B5B29E6B12A727D');
        $this->addSql('ALTER TABLE release_track DROP FOREIGN KEY FK_557D8AD1B12A727D');
        $this->addSql('ALTER TABLE release_translation DROP FOREIGN KEY FK_9FB0F8F7B12A727D');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE news_translation');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE profile_link');
        $this->addSql('DROP TABLE `release`');
        $this->addSql('DROP TABLE release_credit');
        $this->addSql('DROP TABLE release_credit_type');
        $this->addSql('DROP TABLE release_link');
        $this->addSql('DROP TABLE release_track');
        $this->addSql('DROP TABLE release_translation');
    }
}
