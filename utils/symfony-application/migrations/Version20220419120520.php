<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Utils\SymfonyApplication\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220419120520 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('DELETE FROM cms_page_component WHERE page_id is not null');

        $this->addSql('ALTER TABLE cms_page_component DROP CONSTRAINT fk_84196978c4663e4');
        $this->addSql('ALTER TABLE cms_page_custom_meta_tag DROP CONSTRAINT fk_b497e601c4663e4');
        $this->addSql('ALTER TABLE cms_page_open_graph_data DROP CONSTRAINT fk_20a7dc08c4663e4');
        $this->addSql('ALTER TABLE cms_page_seo_data DROP CONSTRAINT fk_61eb5f47c4663e4');

        $this->addSql('DROP TABLE cms_page');
        $this->addSql('DROP TABLE cms_page_custom_meta_tag');
        $this->addSql('DROP TABLE cms_page_open_graph_data');
        $this->addSql('DROP TABLE cms_page_seo_data');

        $this->addSql('ALTER TABLE cms_page_component RENAME TO cms_component');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE cms_component RENAME TO cms_page_component');

        $this->addSql('CREATE TABLE cms_page (id UUID NOT NULL, resource_id UUID NOT NULL, title VARCHAR(255) NOT NULL, is_published BOOLEAN NOT NULL, published_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_d39c1b5d89329d25 ON cms_page (resource_id)');
        $this->addSql('CREATE TABLE cms_page_custom_meta_tag (id UUID NOT NULL, page_id UUID DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, property VARCHAR(255) DEFAULT NULL, content TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_b497e601c4663e4 ON cms_page_custom_meta_tag (page_id)');
        $this->addSql('CREATE TABLE cms_page_open_graph_data (page_id UUID NOT NULL, type VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, description TEXT DEFAULT NULL, image TEXT DEFAULT NULL, PRIMARY KEY(page_id))');
        $this->addSql('CREATE TABLE cms_page_seo_data (page_id UUID NOT NULL, title VARCHAR(255) DEFAULT NULL, description TEXT DEFAULT NULL, keywords TEXT DEFAULT NULL, is_no_index BOOLEAN NOT NULL, is_no_follow BOOLEAN NOT NULL, canonical_url VARCHAR(255) DEFAULT NULL, is_schema_org_enabled BOOLEAN DEFAULT \'false\' NOT NULL, is_included_in_sitemap BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(page_id))');

        $this->addSql('ALTER TABLE cms_page_component ADD CONSTRAINT fk_84196978c4663e4 FOREIGN KEY (page_id) REFERENCES cms_page (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cms_page ADD CONSTRAINT fk_d39c1b5d89329d25 FOREIGN KEY (resource_id) REFERENCES cms_resource (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cms_page_custom_meta_tag ADD CONSTRAINT fk_b497e601c4663e4 FOREIGN KEY (page_id) REFERENCES cms_page (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cms_page_open_graph_data ADD CONSTRAINT fk_20a7dc08c4663e4 FOREIGN KEY (page_id) REFERENCES cms_page (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cms_page_seo_data ADD CONSTRAINT fk_61eb5f47c4663e4 FOREIGN KEY (page_id) REFERENCES cms_page (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
