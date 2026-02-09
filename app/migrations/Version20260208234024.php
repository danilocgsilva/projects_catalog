<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260208234024 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE database_backup_file (id INT AUTO_INCREMENT NOT NULL, database_credential_id INT DEFAULT NULL, file_name VARCHAR(255) NOT NULL, date DATETIME NOT NULL, INDEX IDX_16D04B8FBD138F07 (database_credential_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE database_credential (id INT AUTO_INCREMENT NOT NULL, environment_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, database_name VARCHAR(255) DEFAULT NULL, port INT DEFAULT NULL, host VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, user VARCHAR(255) NOT NULL, INDEX IDX_91F6180A903E3A94 (environment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE environment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE repository_address (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, address VARCHAR(255) NOT NULL, INDEX IDX_ACAC5180166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE database_backup_file ADD CONSTRAINT FK_16D04B8FBD138F07 FOREIGN KEY (database_credential_id) REFERENCES database_credential (id)');
        $this->addSql('ALTER TABLE database_credential ADD CONSTRAINT FK_91F6180A903E3A94 FOREIGN KEY (environment_id) REFERENCES environment (id)');
        $this->addSql('ALTER TABLE repository_address ADD CONSTRAINT FK_ACAC5180166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE database_backup_file DROP FOREIGN KEY FK_16D04B8FBD138F07');
        $this->addSql('ALTER TABLE database_credential DROP FOREIGN KEY FK_91F6180A903E3A94');
        $this->addSql('ALTER TABLE repository_address DROP FOREIGN KEY FK_ACAC5180166D1F9C');
        $this->addSql('DROP TABLE database_backup_file');
        $this->addSql('DROP TABLE database_credential');
        $this->addSql('DROP TABLE environment');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE repository_address');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
