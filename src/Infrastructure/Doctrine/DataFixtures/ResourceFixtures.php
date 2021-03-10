<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\DataFixtures;

class ResourceFixtures extends AbstractFixture
{
    public $tableName = 'cms_resource';

    public $dataFile = 'tests/fixtures/resources.php';
}
