<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240729180431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE news CHANGE date date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE `release` CHANGE release_date release_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE news CHANGE date date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `release` CHANGE release_date release_date DATETIME NOT NULL');
    }
}
