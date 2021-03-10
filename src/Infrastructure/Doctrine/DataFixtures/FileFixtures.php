<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class FileFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public $tableName = 'cms_file';

    public $dataFile = 'tests/fixtures/files.php';

    public function getDependencies(): array
    {
        return [
            ResourceFixtures::class,
        ];
    }
}
