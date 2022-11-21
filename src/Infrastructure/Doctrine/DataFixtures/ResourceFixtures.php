<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ResourceFixtures extends AbstractFixture
{
    public $tableName = 'cms_resource';

    public $dataFile = 'tests/fixtures/resources.php';
}
