<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240625105603 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE news_translation CHANGE news_id news_id INT NOT NULL');
        $this->addSql('ALTER TABLE release_credit CHANGE release_id release_id INT NOT NULL');
        $this->addSql('ALTER TABLE release_link CHANGE release_id release_id INT NOT NULL');
        $this->addSql('ALTER TABLE release_track CHANGE release_id release_id INT NOT NULL');
        $this->addSql('ALTER TABLE release_translation CHANGE release_id release_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE news_translation CHANGE news_id news_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE release_credit CHANGE release_id release_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE release_link CHANGE release_id release_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE release_track CHANGE release_id release_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE release_translation CHANGE release_id release_id INT DEFAULT NULL');
    }
}
