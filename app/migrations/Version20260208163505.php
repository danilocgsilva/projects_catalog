<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260208163505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE database_backup_file ADD database_credential_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE database_backup_file ADD CONSTRAINT FK_16D04B8FBD138F07 FOREIGN KEY (database_credential_id) REFERENCES database_credential (id)');
        $this->addSql('CREATE INDEX IDX_16D04B8FBD138F07 ON database_backup_file (database_credential_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE database_backup_file DROP FOREIGN KEY FK_16D04B8FBD138F07');
        $this->addSql('DROP INDEX IDX_16D04B8FBD138F07 ON database_backup_file');
        $this->addSql('ALTER TABLE database_backup_file DROP database_credential_id');
    }
}
