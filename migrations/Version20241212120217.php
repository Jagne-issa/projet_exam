<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241212120217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE article ADD qte_stock INT NOT NULL');
        $this->addSql('ALTER TABLE article DROP quantitestock');
        $this->addSql('ALTER TABLE article ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE dette DROP CONSTRAINT dette_client_id_fkey');
        $this->addSql('ALTER TABLE dette ADD status VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE dette ADD create_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE dette ADD update_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE dette DROP date');
        $this->addSql('ALTER TABLE dette DROP montant_restant');
        $this->addSql('ALTER TABLE dette DROP etat');
        $this->addSql('ALTER TABLE dette ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE dette ALTER client_id SET NOT NULL');
        $this->addSql('ALTER TABLE dette ALTER montant TYPE DOUBLE PRECISION');
        $this->addSql('ALTER TABLE dette ALTER montant_verser TYPE DOUBLE PRECISION');
        $this->addSql('ALTER TABLE dette ALTER montant_verser DROP DEFAULT');
        $this->addSql('ALTER TABLE dette ALTER montant_verser SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN dette.create_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN dette.update_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE dette ADD CONSTRAINT FK_831BC80819EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD roles JSON NOT NULL');
        $this->addSql('ALTER TABLE "user" DROP login');
        $this->addSql('ALTER TABLE "user" DROP role');
        $this->addSql('ALTER TABLE "user" DROP client_id');
        $this->addSql('ALTER TABLE "user" ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE "user" ALTER email TYPE VARCHAR(180)');
        $this->addSql('ALTER TABLE "user" ALTER password TYPE VARCHAR(255)');
        $this->addSql('ALTER INDEX user_email_key RENAME TO UNIQ_IDENTIFIER_EMAIL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE dette DROP CONSTRAINT FK_831BC80819EB6921');
        $this->addSql('ALTER TABLE dette ADD date DATE NOT NULL');
        $this->addSql('ALTER TABLE dette ADD montant_restant NUMERIC(10, 2) NOT NULL');
        $this->addSql('ALTER TABLE dette ADD etat VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE dette DROP status');
        $this->addSql('ALTER TABLE dette DROP create_at');
        $this->addSql('ALTER TABLE dette DROP update_at');
        $this->addSql('CREATE SEQUENCE dette_id_seq');
        $this->addSql('SELECT setval(\'dette_id_seq\', (SELECT MAX(id) FROM dette))');
        $this->addSql('ALTER TABLE dette ALTER id SET DEFAULT nextval(\'dette_id_seq\')');
        $this->addSql('ALTER TABLE dette ALTER client_id DROP NOT NULL');
        $this->addSql('ALTER TABLE dette ALTER montant TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE dette ALTER montant_verser TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE dette ALTER montant_verser SET DEFAULT \'0.00\'');
        $this->addSql('ALTER TABLE dette ALTER montant_verser DROP NOT NULL');
        $this->addSql('ALTER TABLE dette ADD CONSTRAINT dette_client_id_fkey FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD login VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD role VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" DROP roles');
        $this->addSql('CREATE SEQUENCE user_id_seq');
        $this->addSql('SELECT setval(\'user_id_seq\', (SELECT MAX(id) FROM "user"))');
        $this->addSql('ALTER TABLE "user" ALTER id SET DEFAULT nextval(\'user_id_seq\')');
        $this->addSql('ALTER TABLE "user" ALTER email TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE "user" ALTER password TYPE VARCHAR(100)');
        $this->addSql('ALTER INDEX uniq_identifier_email RENAME TO user_email_key');
        $this->addSql('ALTER TABLE article ADD quantitestock INT DEFAULT 0');
        $this->addSql('ALTER TABLE article DROP description');
        $this->addSql('ALTER TABLE article DROP qte_stock');
        $this->addSql('CREATE SEQUENCE article_id_seq');
        $this->addSql('SELECT setval(\'article_id_seq\', (SELECT MAX(id) FROM article))');
        $this->addSql('ALTER TABLE article ALTER id SET DEFAULT nextval(\'article_id_seq\')');
    }
}
