<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251124191922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844C8C623B4');
        $this->addSql('DROP INDEX IDX_FE38F844C8C623B4 ON appointment');
        $this->addSql('ALTER TABLE appointment DROP appointment_slot_id');
        $this->addSql('ALTER TABLE appointment_slot ADD appointment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE appointment_slot ADD CONSTRAINT FK_BFCDE8A6E5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointment (id)');
        $this->addSql('CREATE INDEX IDX_BFCDE8A6E5B533F9 ON appointment_slot (appointment_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment ADD appointment_slot_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844C8C623B4 FOREIGN KEY (appointment_slot_id) REFERENCES appointment_slot (id)');
        $this->addSql('CREATE INDEX IDX_FE38F844C8C623B4 ON appointment (appointment_slot_id)');
        $this->addSql('ALTER TABLE appointment_slot DROP FOREIGN KEY FK_BFCDE8A6E5B533F9');
        $this->addSql('DROP INDEX IDX_BFCDE8A6E5B533F9 ON appointment_slot');
        $this->addSql('ALTER TABLE appointment_slot DROP appointment_id');
    }
}
