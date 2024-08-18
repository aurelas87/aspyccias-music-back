<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240812150340 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE release_credit_type_translation (locale VARCHAR(2) NOT NULL, release_credit_type_id INT NOT NULL, credit_name VARCHAR(255) NOT NULL, INDEX IDX_FCC2E39FA2ACB85A (release_credit_type_id), PRIMARY KEY(release_credit_type_id, locale)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE release_credit_type_translation ADD CONSTRAINT FK_FCC2E39FA2ACB85A FOREIGN KEY (release_credit_type_id) REFERENCES release_credit_type (id)');
        $this->addSql('DROP INDEX `primary` ON release_credit');
        $this->addSql('ALTER TABLE release_credit ADD PRIMARY KEY (release_id, release_credit_type_id, full_name)');
        $this->addSql('DROP INDEX UNIQ_A0C5558C59E1C13B ON release_credit_type');
        $this->addSql('ALTER TABLE release_credit_type CHANGE credit_name credit_name_key VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A0C5558C256E1A79 ON release_credit_type (credit_name_key)');
        $this->addSql('DROP INDEX `primary` ON release_link');
        $this->addSql('ALTER TABLE release_link ADD PRIMARY KEY (release_id, category, release_link_name_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE release_credit_type_translation DROP FOREIGN KEY FK_FCC2E39FA2ACB85A');
        $this->addSql('DROP TABLE release_credit_type_translation');
        $this->addSql('DROP INDEX UNIQ_A0C5558C256E1A79 ON release_credit_type');
        $this->addSql('ALTER TABLE release_credit_type CHANGE credit_name_key credit_name VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A0C5558C59E1C13B ON release_credit_type (credit_name)');
        $this->addSql('DROP INDEX `PRIMARY` ON release_credit');
        $this->addSql('ALTER TABLE release_credit ADD PRIMARY KEY (release_id, release_credit_type_id)');
        $this->addSql('DROP INDEX `PRIMARY` ON release_link');
        $this->addSql('ALTER TABLE release_link ADD PRIMARY KEY (release_id, release_link_name_id, category)');
    }
}
