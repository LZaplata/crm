<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220128093155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tariff CHANGE billing_at billing_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE unit DROP FOREIGN KEY FK_DCBB0C53357C0A59');
        $this->addSql('DROP INDEX idx_dcbb0c53357c0a59 ON unit');
        $this->addSql('CREATE INDEX IDX_DCBB0C5392348FD2 ON unit (tariff_id)');
        $this->addSql('ALTER TABLE unit ADD CONSTRAINT FK_DCBB0C53357C0A59 FOREIGN KEY (tariff_id) REFERENCES tariff (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tariff CHANGE billing_at billing_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE unit DROP FOREIGN KEY FK_DCBB0C5392348FD2');
        $this->addSql('DROP INDEX idx_dcbb0c5392348fd2 ON unit');
        $this->addSql('CREATE INDEX IDX_DCBB0C53357C0A59 ON unit (tariff_id)');
        $this->addSql('ALTER TABLE unit ADD CONSTRAINT FK_DCBB0C5392348FD2 FOREIGN KEY (tariff_id) REFERENCES tariff (id)');
    }
}
