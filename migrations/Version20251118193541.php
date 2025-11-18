<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251118193541 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EB4C3A3BB');
        $this->addSql('DROP INDEX UNIQ_1ADAD7EB4C3A3BB ON patient');
        $this->addSql('ALTER TABLE patient DROP payment_id');
        $this->addSql('ALTER TABLE payment ADD patient_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('CREATE INDEX IDX_6D28840D6B899279 ON payment (patient_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE patient ADD payment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EB4C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1ADAD7EB4C3A3BB ON patient (payment_id)');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D6B899279');
        $this->addSql('DROP INDEX IDX_6D28840D6B899279 ON payment');
        $this->addSql('ALTER TABLE payment DROP patient_id');
    }
}
