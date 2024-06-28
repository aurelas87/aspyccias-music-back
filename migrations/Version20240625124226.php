<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240625124226 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('SET FOREIGN_KEY_CHECKS = 0');
        $this->addSql('DROP INDEX UNIQ_9D5CF3204180C698 ON news_translation');
        $this->addSql('ALTER TABLE news_translation CHANGE news_id news_id INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9D5CF320B5A459A04180C698 ON news_translation (news_id, locale)');
        $this->addSql('ALTER TABLE release_credit CHANGE release_id release_id INT NOT NULL');
        $this->addSql('ALTER TABLE release_link CHANGE release_id release_id INT NOT NULL');
        $this->addSql('ALTER TABLE release_track CHANGE release_id release_id INT NOT NULL');
        $this->addSql('ALTER TABLE release_translation CHANGE release_id release_id INT NOT NULL');
        $this->addSql('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('SET FOREIGN_KEY_CHECKS = 0');
        $this->addSql('DROP INDEX UNIQ_9D5CF320B5A459A04180C698 ON news_translation');
        $this->addSql('ALTER TABLE news_translation CHANGE news_id news_id INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9D5CF3204180C698 ON news_translation (locale)');
        $this->addSql('ALTER TABLE release_credit CHANGE release_id release_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE release_link CHANGE release_id release_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE release_track CHANGE release_id release_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE release_translation CHANGE release_id release_id INT DEFAULT NULL');
        $this->addSql('SET FOREIGN_KEY_CHECKS = 1');
    }
}
