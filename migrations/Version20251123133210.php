<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251123133210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alarm DROP FOREIGN KEY FK_749F46DD6B899279');
        $this->addSql('DROP INDEX IDX_749F46DD6B899279 ON alarm');
        $this->addSql('ALTER TABLE alarm ADD medicine_id INT DEFAULT NULL, CHANGE patient_id appointment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE alarm ADD CONSTRAINT FK_749F46DDE5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointment (id)');
        $this->addSql('ALTER TABLE alarm ADD CONSTRAINT FK_749F46DD2F7D140A FOREIGN KEY (medicine_id) REFERENCES medicine (id)');
        $this->addSql('CREATE INDEX IDX_749F46DDE5B533F9 ON alarm (appointment_id)');
        $this->addSql('CREATE INDEX IDX_749F46DD2F7D140A ON alarm (medicine_id)');
        $this->addSql('ALTER TABLE medicine DROP FOREIGN KEY FK_58362A8D25830571');
        $this->addSql('DROP INDEX IDX_58362A8D25830571 ON medicine');
        $this->addSql('ALTER TABLE medicine DROP alarm_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alarm DROP FOREIGN KEY FK_749F46DDE5B533F9');
        $this->addSql('ALTER TABLE alarm DROP FOREIGN KEY FK_749F46DD2F7D140A');
        $this->addSql('DROP INDEX IDX_749F46DDE5B533F9 ON alarm');
        $this->addSql('DROP INDEX IDX_749F46DD2F7D140A ON alarm');
        $this->addSql('ALTER TABLE alarm ADD patient_id INT DEFAULT NULL, DROP appointment_id, DROP medicine_id');
        $this->addSql('ALTER TABLE alarm ADD CONSTRAINT FK_749F46DD6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('CREATE INDEX IDX_749F46DD6B899279 ON alarm (patient_id)');
        $this->addSql('ALTER TABLE medicine ADD alarm_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE medicine ADD CONSTRAINT FK_58362A8D25830571 FOREIGN KEY (alarm_id) REFERENCES alarm (id)');
        $this->addSql('CREATE INDEX IDX_58362A8D25830571 ON medicine (alarm_id)');
    }
}
