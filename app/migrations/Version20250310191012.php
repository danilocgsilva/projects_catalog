<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250310191012 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE database_credential ADD environment_id INT DEFAULT NULL, CHANGE user user VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE database_credential ADD CONSTRAINT FK_91F6180A903E3A94 FOREIGN KEY (environment_id) REFERENCES environment (id)');
        $this->addSql('CREATE INDEX IDX_91F6180A903E3A94 ON database_credential (environment_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE database_credential DROP FOREIGN KEY FK_91F6180A903E3A94');
        $this->addSql('DROP INDEX IDX_91F6180A903E3A94 ON database_credential');
        $this->addSql('ALTER TABLE database_credential DROP environment_id, CHANGE user user VARCHAR(255) DEFAULT NULL');
    }
}
