<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251118102539 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medicine ADD attending_physician_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE medicine ADD CONSTRAINT FK_58362A8DD0DED899 FOREIGN KEY (attending_physician_id) REFERENCES doctor (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_58362A8DD0DED899 ON medicine (attending_physician_id)');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_attending_physician');
        $this->addSql('ALTER TABLE patient DROP appointment_id, DROP alarm_id');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBF3B57B97 FOREIGN KEY (emergency_contact_id) REFERENCES emergency_contact (id)');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBA7C41D6F FOREIGN KEY (option_id) REFERENCES `option` (id)');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EB4C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('CREATE INDEX IDX_1ADAD7EBF3B57B97 ON patient (emergency_contact_id)');
        $this->addSql('CREATE INDEX IDX_1ADAD7EBA7C41D6F ON patient (option_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1ADAD7EB4C3A3BB ON patient (payment_id)');
        $this->addSql('DROP INDEX fk_attending_physician ON patient');
        $this->addSql('CREATE INDEX IDX_1ADAD7EBD0DED899 ON patient (attending_physician_id)');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_attending_physician FOREIGN KEY (attending_physician_id) REFERENCES doctor (id)');
        $this->addSql('ALTER TABLE treatment ADD attending_physician_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE treatment ADD CONSTRAINT FK_98013C31D0DED899 FOREIGN KEY (attending_physician_id) REFERENCES doctor (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_98013C31D0DED899 ON treatment (attending_physician_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medicine DROP FOREIGN KEY FK_58362A8DD0DED899');
        $this->addSql('DROP INDEX IDX_58362A8DD0DED899 ON medicine');
        $this->addSql('ALTER TABLE medicine DROP attending_physician_id');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EBF3B57B97');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EBA7C41D6F');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EB4C3A3BB');
        $this->addSql('DROP INDEX IDX_1ADAD7EBF3B57B97 ON patient');
        $this->addSql('DROP INDEX IDX_1ADAD7EBA7C41D6F ON patient');
        $this->addSql('DROP INDEX UNIQ_1ADAD7EB4C3A3BB ON patient');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EBD0DED899');
        $this->addSql('ALTER TABLE patient ADD appointment_id INT DEFAULT NULL, ADD alarm_id INT DEFAULT NULL');
        $this->addSql('DROP INDEX idx_1adad7ebd0ded899 ON patient');
        $this->addSql('CREATE INDEX FK_attending_physician ON patient (attending_physician_id)');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBD0DED899 FOREIGN KEY (attending_physician_id) REFERENCES doctor (id)');
        $this->addSql('ALTER TABLE treatment DROP FOREIGN KEY FK_98013C31D0DED899');
        $this->addSql('DROP INDEX IDX_98013C31D0DED899 ON treatment');
        $this->addSql('ALTER TABLE treatment DROP attending_physician_id');
    }
}
