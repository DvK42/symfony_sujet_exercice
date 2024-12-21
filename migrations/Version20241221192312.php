<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241221192312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chapiter ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE exercise ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE level ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE subject ADD slug VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subject DROP slug');
        $this->addSql('ALTER TABLE level DROP slug');
        $this->addSql('ALTER TABLE exercise DROP slug');
        $this->addSql('ALTER TABLE chapiter DROP slug');
    }
}
