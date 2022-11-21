<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Utils\SymfonyApplication\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211027095258 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE cms_template (
                id UUID NOT NULL,
                name VARCHAR(255) NOT NULL,
                created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY(id)
            )
        ');

        $this->addSql('
            CREATE TABLE cms_template_component (
                id UUID NOT NULL,
                template_id UUID DEFAULT NULL REFERENCES cms_template ON DELETE CASCADE,
                name VARCHAR(255) NOT NULL,
                data JSON NOT NULL,
                component_order INT NOT NULL,
                is_published BOOLEAN NOT NULL,
                PRIMARY KEY(id)
            )
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE cms_template_component');
        $this->addSql('DROP TABLE cms_template');
    }
}
