<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241222131904 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A32B174989D9B62 ON chapiter (slug)');
        $this->addSql('ALTER TABLE comment ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AEDAD51C989D9B62 ON exercise (slug)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9AEACC13989D9B62 ON level (slug)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FBCE3E7A989D9B62 ON subject (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_FBCE3E7A989D9B62 ON subject');
        $this->addSql('DROP INDEX UNIQ_9AEACC13989D9B62 ON level');
        $this->addSql('DROP INDEX UNIQ_AEDAD51C989D9B62 ON exercise');
        $this->addSql('DROP INDEX UNIQ_A32B174989D9B62 ON chapiter');
        $this->addSql('ALTER TABLE comment DROP created_at');
    }
}
