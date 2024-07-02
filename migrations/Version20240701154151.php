<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240701154151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('SET FOREIGN_KEY_CHECKS = 0');
        $this->addSql('ALTER TABLE `release` DROP description_fr, DROP description_en');
        $this->addSql('DROP INDEX UNIQ_9FB0F8F74180C698 ON release_translation');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9FB0F8F7B12A727D4180C698 ON release_translation (release_id, locale)');
        $this->addSql('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('SET FOREIGN_KEY_CHECKS = 0');
        $this->addSql('ALTER TABLE `release` ADD description_fr LONGTEXT DEFAULT NULL, ADD description_en LONGTEXT DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_9FB0F8F7B12A727D4180C698 ON release_translation');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9FB0F8F74180C698 ON release_translation (locale)');
        $this->addSql('SET FOREIGN_KEY_CHECKS = 1');
    }
}
