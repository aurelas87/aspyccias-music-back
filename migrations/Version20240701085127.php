<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240701085127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add slug field to news and release tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE news ADD slug VARCHAR(255) NOT NULL AFTER id');
        $this->addSql('ALTER TABLE `release` ADD slug VARCHAR(255) NOT NULL AFTER id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE news DROP slug');
        $this->addSql('ALTER TABLE `release` DROP slug');
    }
}
