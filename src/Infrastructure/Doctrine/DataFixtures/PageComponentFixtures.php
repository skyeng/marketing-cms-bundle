<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PageComponentFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public $tableName = 'cms_page_component';

    public $dataFile = 'tests/fixtures/page-components.php';

    public function getDependencies(): array
    {
        return [
            PageFixtures::class,
        ];
    }
}
