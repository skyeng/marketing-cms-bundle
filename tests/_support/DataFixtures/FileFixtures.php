<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class FileFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'cms_file';
    public string $dataFile = 'tests/fixtures/files.php';

    /**
     * {@inheritDoc}
     */
    public function getDependencies(): array
    {
        return [
            ResourceFixtures::class,
        ];
    }
}
