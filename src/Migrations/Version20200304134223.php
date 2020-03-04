<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200304134223 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE twitter_direct_message_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE event_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE goody_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE big_prize_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tweet (id BIGINT NOT NULL, event_id INT DEFAULT NULL, user_id VARCHAR(255) NOT NULL, user_avatar VARCHAR(255) NOT NULL, user_screen_name VARCHAR(255) NOT NULL, following BOOLEAN NOT NULL, content TEXT NOT NULL, moderated BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3D660A3B71F7E88B ON tweet (event_id)');
        $this->addSql('CREATE TABLE twitter_direct_message (id INT NOT NULL, recipient_id VARCHAR(255) NOT NULL, message_data VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE event (id INT NOT NULL, big_prize_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, location VARCHAR(255) DEFAULT NULL, call_to_action TEXT DEFAULT NULL, hashtags TEXT NOT NULL, direct_message_data TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7D433E096 ON event (big_prize_id)');
        $this->addSql('CREATE TABLE events_goodies (event_id INT NOT NULL, goody_id INT NOT NULL, PRIMARY KEY(event_id, goody_id))');
        $this->addSql('CREATE INDEX IDX_4518A68371F7E88B ON events_goodies (event_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4518A683D8D5D5F1 ON events_goodies (goody_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(255) NOT NULL, fullname VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE goody (id INT NOT NULL, name VARCHAR(255) NOT NULL, quantity INT NOT NULL, image VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE big_prize (id INT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE tweet ADD CONSTRAINT FK_3D660A3B71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7D433E096 FOREIGN KEY (big_prize_id) REFERENCES big_prize (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE events_goodies ADD CONSTRAINT FK_4518A68371F7E88B FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE events_goodies ADD CONSTRAINT FK_4518A683D8D5D5F1 FOREIGN KEY (goody_id) REFERENCES goody (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tweet DROP CONSTRAINT FK_3D660A3B71F7E88B');
        $this->addSql('ALTER TABLE events_goodies DROP CONSTRAINT FK_4518A68371F7E88B');
        $this->addSql('ALTER TABLE events_goodies DROP CONSTRAINT FK_4518A683D8D5D5F1');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA7D433E096');
        $this->addSql('DROP SEQUENCE twitter_direct_message_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE event_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE goody_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE big_prize_id_seq CASCADE');
        $this->addSql('DROP TABLE tweet');
        $this->addSql('DROP TABLE twitter_direct_message');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE events_goodies');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE goody');
        $this->addSql('DROP TABLE big_prize');
    }
}
