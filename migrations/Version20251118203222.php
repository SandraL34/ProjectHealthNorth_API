<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251118203222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alarm ADD CONSTRAINT FK_749F46DD6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('CREATE INDEX IDX_749F46DD6B899279 ON alarm (patient_id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844A7C41D6F FOREIGN KEY (option_id) REFERENCES `option` (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F8446B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('CREATE INDEX IDX_FE38F844A7C41D6F ON appointment (option_id)');
        $this->addSql('CREATE INDEX IDX_FE38F8446B899279 ON appointment (patient_id)');
        $this->addSql('ALTER TABLE medicine DROP FOREIGN KEY FK_58362A8DD0DED899');
        $this->addSql('ALTER TABLE medicine ADD CONSTRAINT FK_58362A8D6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE medicine ADD CONSTRAINT FK_58362A8D471C0366 FOREIGN KEY (treatment_id) REFERENCES treatment (id)');
        $this->addSql('ALTER TABLE medicine ADD CONSTRAINT FK_58362A8D25830571 FOREIGN KEY (alarm_id) REFERENCES alarm (id)');
        $this->addSql('ALTER TABLE medicine ADD CONSTRAINT FK_58362A8DD0DED899 FOREIGN KEY (attending_physician_id) REFERENCES doctor (id)');
        $this->addSql('CREATE INDEX IDX_58362A8D6B899279 ON medicine (patient_id)');
        $this->addSql('CREATE INDEX IDX_58362A8D471C0366 ON medicine (treatment_id)');
        $this->addSql('CREATE INDEX IDX_58362A8D25830571 ON medicine (alarm_id)');
        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D9D0DED899');
        $this->addSql('ALTER TABLE prescription ADD treatment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D9471C0366 FOREIGN KEY (treatment_id) REFERENCES treatment (id)');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D9D0DED899 FOREIGN KEY (attending_physician_id) REFERENCES doctor (id)');
        $this->addSql('CREATE INDEX IDX_1FBFB8D9471C0366 ON prescription (treatment_id)');
        $this->addSql('ALTER TABLE treatment DROP FOREIGN KEY FK_98013C31D0DED899');
        $this->addSql('ALTER TABLE treatment ADD CONSTRAINT FK_98013C316B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE treatment ADD CONSTRAINT FK_98013C31D0DED899 FOREIGN KEY (attending_physician_id) REFERENCES doctor (id)');
        $this->addSql('CREATE INDEX IDX_98013C316B899279 ON treatment (patient_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alarm DROP FOREIGN KEY FK_749F46DD6B899279');
        $this->addSql('DROP INDEX IDX_749F46DD6B899279 ON alarm');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844A7C41D6F');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F8446B899279');
        $this->addSql('DROP INDEX IDX_FE38F844A7C41D6F ON appointment');
        $this->addSql('DROP INDEX IDX_FE38F8446B899279 ON appointment');
        $this->addSql('ALTER TABLE medicine DROP FOREIGN KEY FK_58362A8D6B899279');
        $this->addSql('ALTER TABLE medicine DROP FOREIGN KEY FK_58362A8D471C0366');
        $this->addSql('ALTER TABLE medicine DROP FOREIGN KEY FK_58362A8D25830571');
        $this->addSql('ALTER TABLE medicine DROP FOREIGN KEY FK_58362A8DD0DED899');
        $this->addSql('DROP INDEX IDX_58362A8D6B899279 ON medicine');
        $this->addSql('DROP INDEX IDX_58362A8D471C0366 ON medicine');
        $this->addSql('DROP INDEX IDX_58362A8D25830571 ON medicine');
        $this->addSql('ALTER TABLE medicine ADD CONSTRAINT FK_58362A8DD0DED899 FOREIGN KEY (attending_physician_id) REFERENCES doctor (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D9471C0366');
        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D9D0DED899');
        $this->addSql('DROP INDEX IDX_1FBFB8D9471C0366 ON prescription');
        $this->addSql('ALTER TABLE prescription DROP treatment_id');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D9D0DED899 FOREIGN KEY (attending_physician_id) REFERENCES doctor (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE treatment DROP FOREIGN KEY FK_98013C316B899279');
        $this->addSql('ALTER TABLE treatment DROP FOREIGN KEY FK_98013C31D0DED899');
        $this->addSql('DROP INDEX IDX_98013C316B899279 ON treatment');
        $this->addSql('ALTER TABLE treatment ADD CONSTRAINT FK_98013C31D0DED899 FOREIGN KEY (attending_physician_id) REFERENCES doctor (id) ON DELETE SET NULL');
    }
}
