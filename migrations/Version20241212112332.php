<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241212112332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE paiement_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE demande_dette_id_seq CASCADE');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$);
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE demande_dette DROP CONSTRAINT demande_dette_client_id_fkey');
        $this->addSql('ALTER TABLE paiement DROP CONSTRAINT paiement_dette_id_fkey');
        $this->addSql('DROP TABLE demande_dette');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('ALTER TABLE article ADD description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE article ADD qte_stock INT NOT NULL');
        $this->addSql('ALTER TABLE article DROP quantitestock');
        $this->addSql('ALTER TABLE article ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE client ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD create_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE client ADD update_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE client DROP montant_du');
        $this->addSql('ALTER TABLE client ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE client ALTER surname TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE client ALTER telephone TYPE VARCHAR(11)');
        $this->addSql('ALTER TABLE client ALTER adresse TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE client ALTER adresse SET NOT NULL');
        $this->addSql('ALTER TABLE client ALTER prenom SET NOT NULL');
        $this->addSql('ALTER TABLE client ALTER prenom TYPE VARCHAR(50)');
        $this->addSql('COMMENT ON COLUMN client.create_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN client.update_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455450FF010 ON client (telephone)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455A76ED395 ON client (user_id)');
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
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT user_client_id_fkey');
        $this->addSql('DROP INDEX IDX_8D93D64919EB6921');
        $this->addSql('ALTER TABLE "user" ADD roles JSON NOT NULL');
        $this->addSql('ALTER TABLE "user" DROP client_id');
        $this->addSql('ALTER TABLE "user" DROP login');
        $this->addSql('ALTER TABLE "user" DROP role');
        $this->addSql('ALTER TABLE "user" ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE "user" ALTER email TYPE VARCHAR(180)');
        $this->addSql('ALTER TABLE "user" ALTER password TYPE VARCHAR(255)');
        $this->addSql('ALTER INDEX user_email_key RENAME TO UNIQ_IDENTIFIER_EMAIL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE paiement_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE demande_dette_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE demande_dette (id SERIAL NOT NULL, client_id INT DEFAULT NULL, date DATE NOT NULL, montant NUMERIC(10, 2) NOT NULL, etat VARCHAR(20) DEFAULT NULL, validation BOOLEAN DEFAULT false, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75C54B2119EB6921 ON demande_dette (client_id)');
        $this->addSql('CREATE TABLE paiement (id SERIAL NOT NULL, dette_id INT DEFAULT NULL, date DATE NOT NULL, montant NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B1DC7A1EE11400A1 ON paiement (dette_id)');
        $this->addSql('ALTER TABLE demande_dette ADD CONSTRAINT demande_dette_client_id_fkey FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT paiement_dette_id_fkey FOREIGN KEY (dette_id) REFERENCES dette (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE article ADD quantitestock INT DEFAULT 0');
        $this->addSql('ALTER TABLE article DROP description');
        $this->addSql('ALTER TABLE article DROP qte_stock');
        $this->addSql('CREATE SEQUENCE article_id_seq');
        $this->addSql('SELECT setval(\'article_id_seq\', (SELECT MAX(id) FROM article))');
        $this->addSql('ALTER TABLE article ALTER id SET DEFAULT nextval(\'article_id_seq\')');
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
        $this->addSql('ALTER TABLE "user" ADD client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD login VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD role VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" DROP roles');
        $this->addSql('CREATE SEQUENCE user_id_seq');
        $this->addSql('SELECT setval(\'user_id_seq\', (SELECT MAX(id) FROM "user"))');
        $this->addSql('ALTER TABLE "user" ALTER id SET DEFAULT nextval(\'user_id_seq\')');
        $this->addSql('ALTER TABLE "user" ALTER email TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE "user" ALTER password TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT user_client_id_fkey FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8D93D64919EB6921 ON "user" (client_id)');
        $this->addSql('ALTER INDEX uniq_identifier_email RENAME TO user_email_key');
        $this->addSql('ALTER TABLE client DROP CONSTRAINT FK_C7440455A76ED395');
        $this->addSql('DROP INDEX UNIQ_C7440455450FF010');
        $this->addSql('DROP INDEX UNIQ_C7440455A76ED395');
        $this->addSql('ALTER TABLE client ADD montant_du NUMERIC(10, 2) DEFAULT \'0.00\'');
        $this->addSql('ALTER TABLE client DROP user_id');
        $this->addSql('ALTER TABLE client DROP create_at');
        $this->addSql('ALTER TABLE client DROP update_at');
        $this->addSql('CREATE SEQUENCE client_id_seq');
        $this->addSql('SELECT setval(\'client_id_seq\', (SELECT MAX(id) FROM client))');
        $this->addSql('ALTER TABLE client ALTER id SET DEFAULT nextval(\'client_id_seq\')');
        $this->addSql('ALTER TABLE client ALTER telephone TYPE VARCHAR(20)');
        $this->addSql('ALTER TABLE client ALTER surname TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE client ALTER prenom DROP NOT NULL');
        $this->addSql('ALTER TABLE client ALTER prenom TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE client ALTER adresse TYPE TEXT');
        $this->addSql('ALTER TABLE client ALTER adresse DROP NOT NULL');
        $this->addSql('ALTER TABLE client ALTER adresse TYPE TEXT');
        $this->addSql('CREATE SEQUENCE articles_id_seq START 1;');
        $this->addSql('ALTER TABLE articles ALTER COLUMN id SET DEFAULT nextval(\'articles_id_seq\');');

    }
}
