<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220127133554 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE unit');
        $this->addSql('ALTER TABLE tariff DROP FOREIGN KEY FK_9465207DDC2902E0');
        $this->addSql('DROP INDEX IDX_9465207DDC2902E0 ON tariff');
        $this->addSql('ALTER TABLE tariff CHANGE client_id_id client_id INT NOT NULL');
        $this->addSql('ALTER TABLE tariff ADD CONSTRAINT FK_9465207D19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_9465207D19EB6921 ON tariff (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE unit (id INT AUTO_INCREMENT NOT NULL, tariff_id_id INT NOT NULL, user_id_id INT NOT NULL, text LONGTEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, amount INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_DCBB0C539D86650F (user_id_id), INDEX IDX_DCBB0C5375E354CD (tariff_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE unit ADD CONSTRAINT FK_DCBB0C5375E354CD FOREIGN KEY (tariff_id_id) REFERENCES tariff (id)');
        $this->addSql('ALTER TABLE unit ADD CONSTRAINT FK_DCBB0C539D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tariff DROP FOREIGN KEY FK_9465207D19EB6921');
        $this->addSql('DROP INDEX IDX_9465207D19EB6921 ON tariff');
        $this->addSql('ALTER TABLE tariff CHANGE client_id client_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE tariff ADD CONSTRAINT FK_9465207DDC2902E0 FOREIGN KEY (client_id_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_9465207DDC2902E0 ON tariff (client_id_id)');
    }
}
