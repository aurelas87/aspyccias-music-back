<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240806095928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE release_link_name (id INT AUTO_INCREMENT NOT NULL, link_name VARCHAR(20) NOT NULL, UNIQUE INDEX UNIQ_D58ABDBAEF64ECAF (link_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP INDEX `primary` ON release_link');
        $this->addSql('ALTER TABLE release_link ADD release_link_name_id INT NOT NULL, CHANGE type category SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE release_link ADD CONSTRAINT FK_7B5B29E6F28E04E8 FOREIGN KEY (release_link_name_id) REFERENCES release_link_name (id)');
        $this->addSql('CREATE INDEX IDX_7B5B29E6F28E04E8 ON release_link (release_link_name_id)');
        $this->addSql('ALTER TABLE release_link ADD PRIMARY KEY (release_id, release_link_name_id, category)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE release_link DROP FOREIGN KEY FK_7B5B29E6F28E04E8');
        $this->addSql('DROP TABLE release_link_name');
        $this->addSql('DROP INDEX IDX_7B5B29E6F28E04E8 ON release_link');
        $this->addSql('DROP INDEX `PRIMARY` ON release_link');
        $this->addSql('ALTER TABLE release_link DROP release_link_name_id, CHANGE category type SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE release_link ADD PRIMARY KEY (release_id, type)');
    }
}
