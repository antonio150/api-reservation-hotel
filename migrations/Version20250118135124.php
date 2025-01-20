<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250118135124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hotel CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE location location VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE room CHANGE type type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE fullname fullname VARCHAR(255) DEFAULT NULL, CHANGE file_path file_path VARCHAR(255) DEFAULT NULL, CHANGE original_name original_name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hotel CHANGE name name VARCHAR(255) DEFAULT \'NULL\', CHANGE location location VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE room CHANGE type type VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE fullname fullname VARCHAR(255) DEFAULT \'NULL\', CHANGE file_path file_path VARCHAR(255) DEFAULT \'NULL\', CHANGE original_name original_name VARCHAR(255) DEFAULT \'NULL\'');
    }
}
