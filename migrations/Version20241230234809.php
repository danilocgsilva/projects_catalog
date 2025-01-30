<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241230234809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Repository entity. Also adds relationship between Projects and repositories addresses.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE repository_address (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, address VARCHAR(255) NOT NULL, INDEX IDX_ACAC5180166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE repository_address ADD CONSTRAINT FK_ACAC5180166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE repository_address DROP FOREIGN KEY FK_ACAC5180166D1F9C');
        $this->addSql('DROP TABLE repository_address');
    }
}
