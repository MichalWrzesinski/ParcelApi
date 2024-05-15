<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240514174358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users ALTER COLUMN warehouse_id DROP NOT NULL');
        $this->addSql('ALTER TABLE delivery_points ALTER type TYPE VARCHAR(20)');
        $this->addSql('ALTER TABLE notifications ADD status VARCHAR(5) NOT NULL');
        $this->addSql('ALTER TABLE parcel_traces ALTER type TYPE VARCHAR(20)');
        $this->addSql('ALTER TABLE parcels ALTER status TYPE VARCHAR(20)');
        $this->addSql('ALTER TABLE users ALTER warehouse_id SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users ALTER COLUMN warehouse_id SET NOT NULL');
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE notifications DROP status');
        $this->addSql('ALTER TABLE parcels ALTER status TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE delivery_points ALTER type TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE parcel_traces ALTER type TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE users ALTER warehouse_id DROP NOT NULL');
    }
}
