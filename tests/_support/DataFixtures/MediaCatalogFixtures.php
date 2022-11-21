<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\DataFixtures;

class MediaCatalogFixtures extends AbstractFixture
{
    public string $tableName = 'cms_media_catalog';
    public string $dataFile = 'tests/fixtures/media-catalogs.php';
}
