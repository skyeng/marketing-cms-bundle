<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Utils\SymfonyApplication\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211005121454 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE cms_model (
                id UUID NOT NULL,
                name VARCHAR(255) NOT NULL,
                PRIMARY KEY(id))
        ');

        $this->addSql('
            CREATE TABLE cms_field (
                id UUID NOT NULL,
                model_id UUID NOT NULL REFERENCES cms_model ON DELETE CASCADE,
                name VARCHAR(255) NOT NULL,
                value text,
                type VARCHAR(255) NOT NULL,
                locale VARCHAR(4),
                PRIMARY KEY(id))
        ');
        $this->addSql('alter table cms_field alter column value type text using value::text');

        $this->addSql('
            ALTER TABLE cms_page_component ADD field_id UUID DEFAULT NULL
        ');

        $this->addSql('
            ALTER TABLE cms_page_component
            ADD CONSTRAINT FK_84196978443707B0
            FOREIGN KEY (field_id) REFERENCES cms_field (id)
            NOT DEFERRABLE INITIALLY IMMEDIATE
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE cms_page_component DROP field_id');
        $this->addSql('alter table cms_field alter column value type varchar using value::varchar');
        $this->addSql('DROP TABLE cms_field');
        $this->addSql('DROP TABLE cms_model');
    }
}
