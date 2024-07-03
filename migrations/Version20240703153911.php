<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240703153911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE release_credit_type (id INT AUTO_INCREMENT NOT NULL, credit_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_A0C5558C59E1C13B (credit_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE news_translation MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_9D5CF320B5A459A04180C698 ON news_translation');
        $this->addSql('DROP INDEX `primary` ON news_translation');
        $this->addSql('ALTER TABLE news_translation DROP id');
        $this->addSql('ALTER TABLE news_translation ADD PRIMARY KEY (news_id, locale)');
        $this->addSql('ALTER TABLE profile MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_8157AA0F4180C698 ON profile');
        $this->addSql('DROP INDEX `primary` ON profile');
        $this->addSql('ALTER TABLE profile DROP id');
        $this->addSql('ALTER TABLE profile ADD PRIMARY KEY (locale)');
        $this->addSql('ALTER TABLE release_credit MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON release_credit');
        $this->addSql('ALTER TABLE release_credit ADD release_credit_type_id INT NOT NULL, DROP id, DROP type');
        $this->addSql('ALTER TABLE release_credit ADD CONSTRAINT FK_D2231413A2ACB85A FOREIGN KEY (release_credit_type_id) REFERENCES release_credit_type (id)');
        $this->addSql('CREATE INDEX IDX_D2231413A2ACB85A ON release_credit (release_credit_type_id)');
        $this->addSql('ALTER TABLE release_credit ADD PRIMARY KEY (release_id, release_credit_type_id)');
        $this->addSql('ALTER TABLE release_link MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON release_link');
        $this->addSql('ALTER TABLE release_link DROP id');
        $this->addSql('ALTER TABLE release_link ADD PRIMARY KEY (release_id, type)');
        $this->addSql('ALTER TABLE release_track MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON release_track');
        $this->addSql('ALTER TABLE release_track DROP id');
        $this->addSql('ALTER TABLE release_track ADD PRIMARY KEY (release_id, title)');
        $this->addSql('ALTER TABLE release_translation MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_9FB0F8F7B12A727D4180C698 ON release_translation');
        $this->addSql('DROP INDEX `primary` ON release_translation');
        $this->addSql('ALTER TABLE release_translation DROP id');
        $this->addSql('ALTER TABLE release_translation ADD PRIMARY KEY (release_id, locale)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE release_credit DROP FOREIGN KEY FK_D2231413A2ACB85A');
        $this->addSql('DROP TABLE release_credit_type');
        $this->addSql('DROP INDEX IDX_D2231413A2ACB85A ON release_credit');
        $this->addSql('ALTER TABLE release_credit ADD id INT AUTO_INCREMENT NOT NULL, ADD type SMALLINT NOT NULL, DROP release_credit_type_id, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE news_translation ADD id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9D5CF320B5A459A04180C698 ON news_translation (news_id, locale)');
        $this->addSql('ALTER TABLE release_track ADD id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE profile ADD id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8157AA0F4180C698 ON profile (locale)');
        $this->addSql('ALTER TABLE release_link ADD id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE release_translation ADD id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9FB0F8F7B12A727D4180C698 ON release_translation (release_id, locale)');
    }
}
