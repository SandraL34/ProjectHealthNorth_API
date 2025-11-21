<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251121161906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment ADD center_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F8445932F377 FOREIGN KEY (center_id) REFERENCES center (id)');
        $this->addSql('CREATE INDEX IDX_FE38F8445932F377 ON appointment (center_id)');
        $this->addSql('ALTER TABLE doctor ADD center_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE doctor ADD CONSTRAINT FK_1FC0F36A5932F377 FOREIGN KEY (center_id) REFERENCES center (id)');
        $this->addSql('CREATE INDEX IDX_1FC0F36A5932F377 ON doctor (center_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F8445932F377');
        $this->addSql('DROP INDEX IDX_FE38F8445932F377 ON appointment');
        $this->addSql('ALTER TABLE appointment DROP center_id');
        $this->addSql('ALTER TABLE doctor DROP FOREIGN KEY FK_1FC0F36A5932F377');
        $this->addSql('DROP INDEX IDX_1FC0F36A5932F377 ON doctor');
        $this->addSql('ALTER TABLE doctor DROP center_id');
    }
}
