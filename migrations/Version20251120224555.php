<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251120224555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE treatment_doctor (treatment_id INT NOT NULL, doctor_id INT NOT NULL, INDEX IDX_DECB9D22471C0366 (treatment_id), INDEX IDX_DECB9D2287F4FB17 (doctor_id), PRIMARY KEY(treatment_id, doctor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE treatment_doctor ADD CONSTRAINT FK_DECB9D22471C0366 FOREIGN KEY (treatment_id) REFERENCES treatment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE treatment_doctor ADD CONSTRAINT FK_DECB9D2287F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844A7C41D6F');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844D0DED899');
        $this->addSql('DROP INDEX IDX_FE38F844D0DED899 ON appointment');
        $this->addSql('DROP INDEX IDX_FE38F844A7C41D6F ON appointment');
        $this->addSql('ALTER TABLE appointment ADD doctor_id INT DEFAULT NULL, DROP option_id, DROP attending_physician_id, DROP specialty_type');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F84487F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id)');
        $this->addSql('CREATE INDEX IDX_FE38F84487F4FB17 ON appointment (doctor_id)');
        $this->addSql('ALTER TABLE medicine DROP FOREIGN KEY FK_58362A8DD0DED899');
        $this->addSql('DROP INDEX IDX_58362A8DD0DED899 ON medicine');
        $this->addSql('ALTER TABLE medicine CHANGE attending_physician_id doctor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE medicine ADD CONSTRAINT FK_58362A8D87F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id)');
        $this->addSql('CREATE INDEX IDX_58362A8D87F4FB17 ON medicine (doctor_id)');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_attending_physician');
        $this->addSql('DROP INDEX IDX_1ADAD7EBD0DED899 ON patient');
        $this->addSql('ALTER TABLE patient CHANGE attending_physician_id doctor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EB87F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id)');
        $this->addSql('CREATE INDEX IDX_1ADAD7EB87F4FB17 ON patient (doctor_id)');
        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D9D0DED899');
        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D96B899279');
        $this->addSql('DROP INDEX IDX_1FBFB8D96B899279 ON prescription');
        $this->addSql('DROP INDEX IDX_1FBFB8D9D0DED899 ON prescription');
        $this->addSql('ALTER TABLE prescription ADD doctor_id INT DEFAULT NULL, DROP attending_physician_id, DROP patient_id');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D987F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id)');
        $this->addSql('CREATE INDEX IDX_1FBFB8D987F4FB17 ON prescription (doctor_id)');
        $this->addSql('ALTER TABLE treatment DROP FOREIGN KEY FK_98013C31D0DED899');
        $this->addSql('DROP INDEX IDX_98013C31D0DED899 ON treatment');
        $this->addSql('ALTER TABLE treatment ADD category VARCHAR(256) DEFAULT NULL, CHANGE attending_physician_id duration INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE treatment_doctor DROP FOREIGN KEY FK_DECB9D22471C0366');
        $this->addSql('ALTER TABLE treatment_doctor DROP FOREIGN KEY FK_DECB9D2287F4FB17');
        $this->addSql('DROP TABLE treatment_doctor');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F84487F4FB17');
        $this->addSql('DROP INDEX IDX_FE38F84487F4FB17 ON appointment');
        $this->addSql('ALTER TABLE appointment ADD attending_physician_id INT DEFAULT NULL, ADD specialty_type VARCHAR(256) DEFAULT NULL, CHANGE doctor_id option_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844A7C41D6F FOREIGN KEY (option_id) REFERENCES `option` (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844D0DED899 FOREIGN KEY (attending_physician_id) REFERENCES doctor (id)');
        $this->addSql('CREATE INDEX IDX_FE38F844D0DED899 ON appointment (attending_physician_id)');
        $this->addSql('CREATE INDEX IDX_FE38F844A7C41D6F ON appointment (option_id)');
        $this->addSql('ALTER TABLE medicine DROP FOREIGN KEY FK_58362A8D87F4FB17');
        $this->addSql('DROP INDEX IDX_58362A8D87F4FB17 ON medicine');
        $this->addSql('ALTER TABLE medicine CHANGE doctor_id attending_physician_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE medicine ADD CONSTRAINT FK_58362A8DD0DED899 FOREIGN KEY (attending_physician_id) REFERENCES doctor (id)');
        $this->addSql('CREATE INDEX IDX_58362A8DD0DED899 ON medicine (attending_physician_id)');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EB87F4FB17');
        $this->addSql('DROP INDEX IDX_1ADAD7EB87F4FB17 ON patient');
        $this->addSql('ALTER TABLE patient CHANGE doctor_id attending_physician_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_attending_physician FOREIGN KEY (attending_physician_id) REFERENCES doctor (id)');
        $this->addSql('CREATE INDEX IDX_1ADAD7EBD0DED899 ON patient (attending_physician_id)');
        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D987F4FB17');
        $this->addSql('DROP INDEX IDX_1FBFB8D987F4FB17 ON prescription');
        $this->addSql('ALTER TABLE prescription ADD patient_id INT DEFAULT NULL, CHANGE doctor_id attending_physician_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D9D0DED899 FOREIGN KEY (attending_physician_id) REFERENCES doctor (id)');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D96B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('CREATE INDEX IDX_1FBFB8D96B899279 ON prescription (patient_id)');
        $this->addSql('CREATE INDEX IDX_1FBFB8D9D0DED899 ON prescription (attending_physician_id)');
        $this->addSql('ALTER TABLE treatment DROP category, CHANGE duration attending_physician_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE treatment ADD CONSTRAINT FK_98013C31D0DED899 FOREIGN KEY (attending_physician_id) REFERENCES doctor (id)');
        $this->addSql('CREATE INDEX IDX_98013C31D0DED899 ON treatment (attending_physician_id)');
    }
}
