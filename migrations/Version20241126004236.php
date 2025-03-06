<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241126004236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `release` DROP artwork_front_image, CHANGE artwork_back_image artwork_back_image TINYINT(1) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9E47031D989D9B62 ON `release` (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_9E47031D989D9B62 ON `release`');
        $this->addSql('ALTER TABLE `release` ADD artwork_front_image VARCHAR(255) NOT NULL, CHANGE artwork_back_image artwork_back_image VARCHAR(255) DEFAULT NULL');
    }
}
