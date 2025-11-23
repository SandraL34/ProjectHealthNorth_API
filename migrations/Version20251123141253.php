<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251123141253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F8445932F377');
        $this->addSql('DROP INDEX IDX_FE38F8445932F377 ON appointment');
        $this->addSql('ALTER TABLE appointment DROP center_id');
        $this->addSql('ALTER TABLE medicine DROP FOREIGN KEY FK_58362A8D6B899279');
        $this->addSql('ALTER TABLE medicine DROP FOREIGN KEY FK_58362A8D87F4FB17');
        $this->addSql('ALTER TABLE medicine DROP FOREIGN KEY FK_58362A8D471C0366');
        $this->addSql('DROP INDEX IDX_58362A8D6B899279 ON medicine');
        $this->addSql('DROP INDEX IDX_58362A8D471C0366 ON medicine');
        $this->addSql('DROP INDEX IDX_58362A8D87F4FB17 ON medicine');
        $this->addSql('ALTER TABLE medicine ADD prescription_id INT DEFAULT NULL, DROP patient_id, DROP doctor_id, DROP treatment_id');
        $this->addSql('ALTER TABLE medicine ADD CONSTRAINT FK_58362A8D93DB413D FOREIGN KEY (prescription_id) REFERENCES prescription (id)');
        $this->addSql('CREATE INDEX IDX_58362A8D93DB413D ON medicine (prescription_id)');
        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D9471C0366');
        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D987F4FB17');
        $this->addSql('DROP INDEX IDX_1FBFB8D987F4FB17 ON prescription');
        $this->addSql('DROP INDEX IDX_1FBFB8D9471C0366 ON prescription');
        $this->addSql('ALTER TABLE prescription ADD appointment_id INT DEFAULT NULL, DROP treatment_id, DROP doctor_id');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D9E5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointment (id)');
        $this->addSql('CREATE INDEX IDX_1FBFB8D9E5B533F9 ON prescription (appointment_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment ADD center_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F8445932F377 FOREIGN KEY (center_id) REFERENCES center (id)');
        $this->addSql('CREATE INDEX IDX_FE38F8445932F377 ON appointment (center_id)');
        $this->addSql('ALTER TABLE medicine DROP FOREIGN KEY FK_58362A8D93DB413D');
        $this->addSql('DROP INDEX IDX_58362A8D93DB413D ON medicine');
        $this->addSql('ALTER TABLE medicine ADD doctor_id INT DEFAULT NULL, ADD treatment_id INT DEFAULT NULL, CHANGE prescription_id patient_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE medicine ADD CONSTRAINT FK_58362A8D6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE medicine ADD CONSTRAINT FK_58362A8D87F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id)');
        $this->addSql('ALTER TABLE medicine ADD CONSTRAINT FK_58362A8D471C0366 FOREIGN KEY (treatment_id) REFERENCES treatment (id)');
        $this->addSql('CREATE INDEX IDX_58362A8D6B899279 ON medicine (patient_id)');
        $this->addSql('CREATE INDEX IDX_58362A8D471C0366 ON medicine (treatment_id)');
        $this->addSql('CREATE INDEX IDX_58362A8D87F4FB17 ON medicine (doctor_id)');
        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D9E5B533F9');
        $this->addSql('DROP INDEX IDX_1FBFB8D9E5B533F9 ON prescription');
        $this->addSql('ALTER TABLE prescription ADD doctor_id INT DEFAULT NULL, CHANGE appointment_id treatment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D9471C0366 FOREIGN KEY (treatment_id) REFERENCES treatment (id)');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D987F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id)');
        $this->addSql('CREATE INDEX IDX_1FBFB8D987F4FB17 ON prescription (doctor_id)');
        $this->addSql('CREATE INDEX IDX_1FBFB8D9471C0366 ON prescription (treatment_id)');
    }
}
