<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Utils\SymfonyApplication\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210319074733 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE cms_file (
                id UUID NOT NULL,
                resource_id UUID DEFAULT NULL,
                content_type VARCHAR(255) NOT NULL,
                content TEXT NOT NULL,
                cache_time VARCHAR(255) NOT NULL,
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4B099B6D89329D25 ON cms_file (resource_id)');

        $this->addSql('
            CREATE TABLE cms_redirect (
                id UUID NOT NULL,
                resource_id UUID DEFAULT NULL,
                target_url TEXT NOT NULL,
                http_code INT NOT NULL,
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A9E252D689329D25 ON cms_redirect (resource_id)');

        $this->addSql('
            CREATE TABLE cms_resource (
                id UUID NOT NULL,
                uri VARCHAR(255) NOT NULL,
                type VARCHAR(255) NOT NULL,
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D67F38EB841CB121 ON cms_resource (uri)');

        $this->addSql(
            'ALTER TABLE cms_file ADD CONSTRAINT FK_4B099B6D89329D25 FOREIGN KEY (resource_id) REFERENCES cms_resource (id) NOT DEFERRABLE INITIALLY IMMEDIATE',
        );
        $this->addSql(
            'ALTER TABLE cms_redirect ADD CONSTRAINT FK_A9E252D689329D25 FOREIGN KEY (resource_id) REFERENCES cms_resource (id) NOT DEFERRABLE INITIALLY IMMEDIATE',
        );

        $this->addSql('
             CREATE TABLE cms_page (
                 id UUID NOT NULL,
                 resource_id UUID NOT NULL,
                 title VARCHAR(255) NOT NULL,
                 is_published BOOLEAN NOT NULL,
                 published_at TIMESTAMP(0) WITHOUT TIME ZONE,
                 created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                 updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                 PRIMARY KEY(id)
             )
         ');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D39C1B5D89329D25 ON cms_page (resource_id)');
        $this->addSql('
             CREATE TABLE cms_page_component (
                 id UUID NOT NULL,
                 page_id UUID DEFAULT NULL,
                 name VARCHAR(255) NOT NULL,
                 data JSON NOT NULL,
                 component_order INT NOT NULL,
                 is_published BOOLEAN NOT NULL,
                 PRIMARY KEY(id)
             )');
        $this->addSql('CREATE INDEX IDX_84196978C4663E4 ON cms_page_component (page_id)');
        $this->addSql('COMMENT ON COLUMN cms_page_component.data IS \'(DC2Type:json_array)\'');
        $this->addSql('
             CREATE TABLE cms_page_custom_meta_tag (
                 id UUID NOT NULL,
                 page_id UUID DEFAULT NULL,
                 name VARCHAR(255) DEFAULT NULL,
                 property VARCHAR(255) DEFAULT NULL,
                 content TEXT NOT NULL,
                 PRIMARY KEY(id)
             )');
        $this->addSql('CREATE INDEX IDX_B497E601C4663E4 ON cms_page_custom_meta_tag (page_id)');
        $this->addSql('
             CREATE TABLE cms_page_open_graph_data (
                 page_id UUID NOT NULL,
                 type VARCHAR(255) DEFAULT NULL,
                 url VARCHAR(255) DEFAULT NULL,
                 title VARCHAR(255) DEFAULT NULL,
                 description TEXT DEFAULT NULL,
                 image TEXT DEFAULT NULL,
                 PRIMARY KEY(page_id)
             )');
        $this->addSql('
             CREATE TABLE cms_page_seo_data (
                 page_id UUID NOT NULL,
                 title VARCHAR(255) DEFAULT NULL,
                 description TEXT DEFAULT NULL,
                 keywords TEXT DEFAULT NULL,
                 is_no_index BOOLEAN NOT NULL,
                 is_no_follow BOOLEAN NOT NULL,
                 PRIMARY KEY(page_id)
             )');

        $this->addSql(
            'ALTER TABLE cms_page ADD CONSTRAINT FK_D39C1B5D89329D25 FOREIGN KEY (resource_id) REFERENCES cms_resource (id) NOT DEFERRABLE INITIALLY IMMEDIATE',
        );
        $this->addSql(
            'ALTER TABLE cms_page_component ADD CONSTRAINT FK_84196978C4663E4 FOREIGN KEY (page_id) REFERENCES cms_page (id) NOT DEFERRABLE INITIALLY IMMEDIATE',
        );
        $this->addSql(
            'ALTER TABLE cms_page_custom_meta_tag ADD CONSTRAINT FK_B497E601C4663E4 FOREIGN KEY (page_id) REFERENCES cms_page (id) NOT DEFERRABLE INITIALLY IMMEDIATE',
        );
        $this->addSql(
            'ALTER TABLE cms_page_open_graph_data ADD CONSTRAINT FK_20A7DC08C4663E4 FOREIGN KEY (page_id) REFERENCES cms_page (id) NOT DEFERRABLE INITIALLY IMMEDIATE',
        );
        $this->addSql(
            'ALTER TABLE cms_page_seo_data ADD CONSTRAINT FK_61EB5F47C4663E4 FOREIGN KEY (page_id) REFERENCES cms_page (id) NOT DEFERRABLE INITIALLY IMMEDIATE',
        );

        $this->addSql(
            'CREATE TABLE cms_media_file (id UUID NOT NULL, catalog_id UUID DEFAULT NULL, title VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, storage VARCHAR(255) NOT NULL, original_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))',
        );
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7C1892465E237E06 ON cms_media_file (name)');
        $this->addSql('CREATE INDEX IDX_7C189246CC3C66FC ON cms_media_file (catalog_id)');
        $this->addSql('CREATE TABLE cms_media_catalog (id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CAAA0FDB5E237E06 ON cms_media_catalog (name)');
        $this->addSql(
            'ALTER TABLE cms_media_file ADD CONSTRAINT FK_7C189246CC3C66FC FOREIGN KEY (catalog_id) REFERENCES cms_media_catalog (id) NOT DEFERRABLE INITIALLY IMMEDIATE',
        );

        $this->addSql('ALTER TABLE cms_page_seo_data ADD canonical_url VARCHAR(255) DEFAULT NULL');

        $this->addSql(
            'ALTER TABLE cms_media_file ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP',
        );
        $this->addSql(
            'ALTER TABLE cms_redirect ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP',
        );
        $this->addSql(
            'ALTER TABLE cms_file ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP',
        );

        $this->addSql('ALTER TABLE cms_page_seo_data ADD is_included_in_sitemap BOOLEAN NOT NULL DEFAULT false');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE cms_page_seo_data DROP canonical_url');

        $this->addSql('ALTER TABLE cms_redirect DROP created_at');
        $this->addSql('ALTER TABLE cms_media_file DROP created_at');
        $this->addSql('ALTER TABLE cms_file DROP created_at');

        $this->addSql('ALTER TABLE cms_page_seo_data DROP is_included_in_sitemap');

        $this->addSql('DROP TABLE cms_media_file');
        $this->addSql('DROP TABLE cms_media_catalog');

        $this->addSql('ALTER TABLE cms_page_component DROP CONSTRAINT FK_84196978C4663E4');
        $this->addSql('ALTER TABLE cms_page_custom_meta_tag DROP CONSTRAINT FK_B497E601C4663E4');
        $this->addSql('ALTER TABLE cms_page_open_graph_data DROP CONSTRAINT FK_20A7DC08C4663E4');
        $this->addSql('ALTER TABLE cms_page_seo_data DROP CONSTRAINT FK_61EB5F47C4663E4');
        $this->addSql('DROP TABLE cms_page');
        $this->addSql('DROP TABLE cms_page_component');
        $this->addSql('DROP TABLE cms_page_custom_meta_tag');
        $this->addSql('DROP TABLE cms_page_open_graph_data');
        $this->addSql('DROP TABLE cms_page_seo_data');

        $this->addSql('ALTER TABLE cms_file DROP CONSTRAINT FK_4B099B6D89329D25');
        $this->addSql('ALTER TABLE cms_redirect DROP CONSTRAINT FK_A9E252D689329D25');
        $this->addSql('DROP TABLE cms_file');
        $this->addSql('DROP TABLE cms_redirect');
        $this->addSql('DROP TABLE cms_resource');
    }
}
