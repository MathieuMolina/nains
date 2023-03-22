<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230321093157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE topic DROP FOREIGN KEY FK_9D40DE1B61220EA6');
        $this->addSql('DROP INDEX IDX_9D40DE1B61220EA6 ON topic');
        $this->addSql('ALTER TABLE topic ADD topic_id INT DEFAULT NULL, DROP creator_id, DROP created_at, DROP views, DROP content');
        $this->addSql('ALTER TABLE topic ADD CONSTRAINT FK_9D40DE1B1F55203D FOREIGN KEY (topic_id) REFERENCES topic (id)');
        $this->addSql('CREATE INDEX IDX_9D40DE1B1F55203D ON topic (topic_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE topic DROP FOREIGN KEY FK_9D40DE1B1F55203D');
        $this->addSql('DROP INDEX IDX_9D40DE1B1F55203D ON topic');
        $this->addSql('ALTER TABLE topic ADD creator_id INT NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD views INT NOT NULL, ADD content LONGTEXT DEFAULT NULL, DROP topic_id');
        $this->addSql('ALTER TABLE topic ADD CONSTRAINT FK_9D40DE1B61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_9D40DE1B61220EA6 ON topic (creator_id)');
    }
}
