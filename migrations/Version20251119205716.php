<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251119205716 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment ADD attending_physician_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844D0DED899 FOREIGN KEY (attending_physician_id) REFERENCES doctor (id)');
        $this->addSql('CREATE INDEX IDX_FE38F844D0DED899 ON appointment (attending_physician_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844D0DED899');
        $this->addSql('DROP INDEX IDX_FE38F844D0DED899 ON appointment');
        $this->addSql('ALTER TABLE appointment DROP attending_physician_id');
    }
}
