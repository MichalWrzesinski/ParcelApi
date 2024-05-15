<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240515134051 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE delivery_points (id UUID NOT NULL, type VARCHAR(30) NOT NULL, name VARCHAR(30) NOT NULL, post_code VARCHAR(6) NOT NULL, city VARCHAR(30) NOT NULL, address VARCHAR(50) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN delivery_points.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE employees_delivery_points (delivery_point_id UUID NOT NULL, employee_id UUID NOT NULL, PRIMARY KEY(delivery_point_id, employee_id))');
        $this->addSql('CREATE INDEX IDX_2E20F4A5A1492FCE ON employees_delivery_points (delivery_point_id)');
        $this->addSql('CREATE INDEX IDX_2E20F4A58C03F15C ON employees_delivery_points (employee_id)');
        $this->addSql('COMMENT ON COLUMN employees_delivery_points.delivery_point_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN employees_delivery_points.employee_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE notifications (id UUID NOT NULL, user_id UUID NOT NULL, description TEXT NOT NULL, status VARCHAR(30) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6000B0D3A76ED395 ON notifications (user_id)');
        $this->addSql('COMMENT ON COLUMN notifications.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN notifications.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE parcel_traces (id UUID NOT NULL, type VARCHAR(30) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN parcel_traces.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE parcels (id UUID NOT NULL, sender_id UUID DEFAULT NULL, receiver_id UUID DEFAULT NULL, sending_point_id UUID DEFAULT NULL, receiving_point_id UUID DEFAULT NULL, status VARCHAR(30) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5675350EF624B39D ON parcels (sender_id)');
        $this->addSql('CREATE INDEX IDX_5675350ECD53EDB6 ON parcels (receiver_id)');
        $this->addSql('CREATE INDEX IDX_5675350E6E23B675 ON parcels (sending_point_id)');
        $this->addSql('CREATE INDEX IDX_5675350EF9C5F1EB ON parcels (receiving_point_id)');
        $this->addSql('COMMENT ON COLUMN parcels.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN parcels.sender_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN parcels.receiver_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN parcels.sending_point_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN parcels.receiving_point_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE users (id UUID NOT NULL, warehouse_id UUID DEFAULT NULL, phone VARCHAR(12) NOT NULL, email VARCHAR(100) NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, type VARCHAR(255) NOT NULL, position VARCHAR(30) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9444F97DD ON users (phone)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('CREATE INDEX IDX_1483A5E95080ECDE ON users (warehouse_id)');
        $this->addSql('COMMENT ON COLUMN users.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN users.warehouse_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE warehouses (id UUID NOT NULL, name VARCHAR(30) NOT NULL, post_code VARCHAR(6) NOT NULL, city VARCHAR(30) NOT NULL, address VARCHAR(50) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN warehouses.id IS \'(DC2Type:uuid)\'');
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
        $this->addSql('ALTER TABLE employees_delivery_points ADD CONSTRAINT FK_2E20F4A5A1492FCE FOREIGN KEY (delivery_point_id) REFERENCES delivery_points (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE employees_delivery_points ADD CONSTRAINT FK_2E20F4A58C03F15C FOREIGN KEY (employee_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D3A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE parcels ADD CONSTRAINT FK_5675350EF624B39D FOREIGN KEY (sender_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE parcels ADD CONSTRAINT FK_5675350ECD53EDB6 FOREIGN KEY (receiver_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE parcels ADD CONSTRAINT FK_5675350E6E23B675 FOREIGN KEY (sending_point_id) REFERENCES delivery_points (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE parcels ADD CONSTRAINT FK_5675350EF9C5F1EB FOREIGN KEY (receiving_point_id) REFERENCES delivery_points (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E95080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouses (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE employees_delivery_points DROP CONSTRAINT FK_2E20F4A5A1492FCE');
        $this->addSql('ALTER TABLE employees_delivery_points DROP CONSTRAINT FK_2E20F4A58C03F15C');
        $this->addSql('ALTER TABLE notifications DROP CONSTRAINT FK_6000B0D3A76ED395');
        $this->addSql('ALTER TABLE parcels DROP CONSTRAINT FK_5675350EF624B39D');
        $this->addSql('ALTER TABLE parcels DROP CONSTRAINT FK_5675350ECD53EDB6');
        $this->addSql('ALTER TABLE parcels DROP CONSTRAINT FK_5675350E6E23B675');
        $this->addSql('ALTER TABLE parcels DROP CONSTRAINT FK_5675350EF9C5F1EB');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E95080ECDE');
        $this->addSql('DROP TABLE delivery_points');
        $this->addSql('DROP TABLE employees_delivery_points');
        $this->addSql('DROP TABLE notifications');
        $this->addSql('DROP TABLE parcel_traces');
        $this->addSql('DROP TABLE parcels');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE warehouses');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
