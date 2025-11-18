<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251118103738 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medicine ADD treatment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE prescription ADD attending_physician_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D9D0DED899 FOREIGN KEY (attending_physician_id) REFERENCES doctor (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_1FBFB8D9D0DED899 ON prescription (attending_physician_id)');
        $this->addSql('ALTER TABLE treatment DROP medicine_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medicine DROP treatment_id');
        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D9D0DED899');
        $this->addSql('DROP INDEX IDX_1FBFB8D9D0DED899 ON prescription');
        $this->addSql('ALTER TABLE prescription DROP attending_physician_id');
        $this->addSql('ALTER TABLE treatment ADD medicine_id INT DEFAULT NULL');
    }
}
