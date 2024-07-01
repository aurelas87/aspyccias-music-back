<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240625101040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Refacto translations tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE news_translation (id INT AUTO_INCREMENT NOT NULL, news_id INT DEFAULT NULL, locale VARCHAR(2) NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_9D5CF3204180C698 (locale), INDEX IDX_9D5CF320B5A459A0 (news_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE release_translation (id INT AUTO_INCREMENT NOT NULL, release_id INT DEFAULT NULL, locale VARCHAR(2) NOT NULL, description LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_9FB0F8F74180C698 (locale), INDEX IDX_9FB0F8F7B12A727D (release_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE news_translation ADD CONSTRAINT FK_9D5CF320B5A459A0 FOREIGN KEY (news_id) REFERENCES news (id)');
        $this->addSql('ALTER TABLE release_translation ADD CONSTRAINT FK_9FB0F8F7B12A727D FOREIGN KEY (release_id) REFERENCES `release` (id)');
        $this->addSql('ALTER TABLE release_translations DROP FOREIGN KEY FK_3848184CB12A727D');
        $this->addSql('ALTER TABLE news_translations DROP FOREIGN KEY FK_20FDB330B5A459A0');
        $this->addSql('DROP TABLE release_translations');
        $this->addSql('DROP TABLE news_translations');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE release_translations (id INT AUTO_INCREMENT NOT NULL, release_id INT DEFAULT NULL, locale VARCHAR(2) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_3848184C4180C698 (locale), INDEX IDX_3848184CB12A727D (release_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE news_translations (id INT AUTO_INCREMENT NOT NULL, news_id INT DEFAULT NULL, locale VARCHAR(2) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, content LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_20FDB3304180C698 (locale), INDEX IDX_20FDB330B5A459A0 (news_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE release_translations ADD CONSTRAINT FK_3848184CB12A727D FOREIGN KEY (release_id) REFERENCES `release` (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE news_translations ADD CONSTRAINT FK_20FDB330B5A459A0 FOREIGN KEY (news_id) REFERENCES news (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE news_translation DROP FOREIGN KEY FK_9D5CF320B5A459A0');
        $this->addSql('ALTER TABLE release_translation DROP FOREIGN KEY FK_9FB0F8F7B12A727D');
        $this->addSql('DROP TABLE news_translation');
        $this->addSql('DROP TABLE release_translation');
    }
}
