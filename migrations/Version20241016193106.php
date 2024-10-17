<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241016193106 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE articles_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE utilisateurs_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE clients_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE dettes_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE paiements_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE demandes_dette_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE articles_dette_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE client_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE dette_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE client (id INT NOT NULL, user_id INT DEFAULT NULL, telephone VARCHAR(11) NOT NULL, surname VARCHAR(50) NOT NULL, adresse VARCHAR(100) NOT NULL, create_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, update_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455450FF010 ON client (telephone)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455E7769B0F ON client (surname)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455A76ED395 ON client (user_id)');
        $this->addSql('COMMENT ON COLUMN client.create_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN client.update_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE dette (id INT NOT NULL, client_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, montant_verser DOUBLE PRECISION NOT NULL, create_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, update_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_831BC80819EB6921 ON dette (client_id)');
        $this->addSql('COMMENT ON COLUMN dette.create_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN dette.update_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, login VARCHAR(100) NOT NULL, password VARCHAR(255) NOT NULL, create_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, update_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_blocked BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649AA08CB10 ON "user" (login)');
        $this->addSql('COMMENT ON COLUMN "user".create_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".update_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dette ADD CONSTRAINT FK_831BC80819EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE articles_dette DROP CONSTRAINT articles_dette_dette_id_fkey');
        $this->addSql('ALTER TABLE articles_dette DROP CONSTRAINT articles_dette_article_id_fkey');
        $this->addSql('ALTER TABLE demandes_dette DROP CONSTRAINT demandes_dette_client_id_fkey');
        $this->addSql('ALTER TABLE clients DROP CONSTRAINT clients_utilisateur_id_fkey');
        $this->addSql('ALTER TABLE dettes DROP CONSTRAINT dettes_client_id_fkey');
        $this->addSql('ALTER TABLE paiements DROP CONSTRAINT paiements_dette_id_fkey');
        $this->addSql('DROP TABLE articles_dette');
        $this->addSql('DROP TABLE demandes_dette');
        $this->addSql('DROP TABLE utilisateurs');
        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE clients');
        $this->addSql('DROP TABLE dettes');
        $this->addSql('DROP TABLE paiements');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE client_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE dette_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE articles_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE utilisateurs_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE clients_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE dettes_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE paiements_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE demandes_dette_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE articles_dette_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE articles_dette (id SERIAL NOT NULL, dette_id INT NOT NULL, article_id INT NOT NULL, quantite INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6BF9E0FCE11400A1 ON articles_dette (dette_id)');
        $this->addSql('CREATE INDEX IDX_6BF9E0FC7294869C ON articles_dette (article_id)');
        $this->addSql('CREATE TABLE demandes_dette (id SERIAL NOT NULL, client_id INT NOT NULL, etat VARCHAR(20) DEFAULT NULL, date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_437A319319EB6921 ON demandes_dette (client_id)');
        $this->addSql('CREATE TABLE utilisateurs (id SERIAL NOT NULL, email VARCHAR(100) NOT NULL, login VARCHAR(50) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(20) DEFAULT NULL, actif BOOLEAN DEFAULT true, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX utilisateurs_login_key ON utilisateurs (login)');
        $this->addSql('CREATE UNIQUE INDEX utilisateurs_email_key ON utilisateurs (email)');
        $this->addSql('CREATE TABLE articles (id SERIAL NOT NULL, nom VARCHAR(100) NOT NULL, description TEXT DEFAULT NULL, qte_stock INT NOT NULL, prix NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE clients (id SERIAL NOT NULL, utilisateur_id INT DEFAULT NULL, surname VARCHAR(100) NOT NULL, telephone VARCHAR(15) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX clients_telephone_key ON clients (telephone)');
        $this->addSql('CREATE INDEX IDX_C82E74FB88E14F ON clients (utilisateur_id)');
        $this->addSql('CREATE TABLE dettes (id SERIAL NOT NULL, client_id INT NOT NULL, date DATE NOT NULL, montant NUMERIC(10, 2) NOT NULL, montant_verse NUMERIC(10, 2) DEFAULT \'0\', PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_15565CF119EB6921 ON dettes (client_id)');
        $this->addSql('CREATE TABLE paiements (id SERIAL NOT NULL, dette_id INT NOT NULL, date DATE NOT NULL, montant NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E1B02E12E11400A1 ON paiements (dette_id)');
        $this->addSql('ALTER TABLE articles_dette ADD CONSTRAINT articles_dette_dette_id_fkey FOREIGN KEY (dette_id) REFERENCES dettes (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE articles_dette ADD CONSTRAINT articles_dette_article_id_fkey FOREIGN KEY (article_id) REFERENCES articles (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE demandes_dette ADD CONSTRAINT demandes_dette_client_id_fkey FOREIGN KEY (client_id) REFERENCES clients (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE clients ADD CONSTRAINT clients_utilisateur_id_fkey FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dettes ADD CONSTRAINT dettes_client_id_fkey FOREIGN KEY (client_id) REFERENCES clients (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE paiements ADD CONSTRAINT paiements_dette_id_fkey FOREIGN KEY (dette_id) REFERENCES dettes (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE client DROP CONSTRAINT FK_C7440455A76ED395');
        $this->addSql('ALTER TABLE dette DROP CONSTRAINT FK_831BC80819EB6921');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE dette');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
