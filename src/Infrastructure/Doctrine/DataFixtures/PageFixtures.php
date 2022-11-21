<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PageFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public $tableName = 'cms_page';

    public $dataFile = 'tests/fixtures/pages.php';

    public function getDependencies(): array
    {
        return [
            ResourceFixtures::class,
        ];
    }
}
