<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251123123631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appointment_treatment (appointment_id INT NOT NULL, treatment_id INT NOT NULL, INDEX IDX_D8B5238E5B533F9 (appointment_id), INDEX IDX_D8B5238471C0366 (treatment_id), PRIMARY KEY(appointment_id, treatment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE appointment_treatment ADD CONSTRAINT FK_D8B5238E5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE appointment_treatment ADD CONSTRAINT FK_D8B5238471C0366 FOREIGN KEY (treatment_id) REFERENCES treatment (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment_treatment DROP FOREIGN KEY FK_D8B5238E5B533F9');
        $this->addSql('ALTER TABLE appointment_treatment DROP FOREIGN KEY FK_D8B5238471C0366');
        $this->addSql('DROP TABLE appointment_treatment');
    }
}
