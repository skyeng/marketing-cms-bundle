<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Utils\SymfonyApplication\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220119162549 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE cms_page_seo_data ADD is_schema_org_enabled BOOLEAN NOT NULL DEFAULT false');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE cms_page_seo_data DROP is_schema_org_enabled');
    }
}
