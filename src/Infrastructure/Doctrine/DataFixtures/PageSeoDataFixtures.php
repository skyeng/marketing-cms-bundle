<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PageSeoDataFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public $tableName = 'cms_page_seo_data';

    public $dataFile = 'tests/fixtures/page-seo-data.php';

    public function getDependencies(): array
    {
        return [
            PageFixtures::class,
        ];
    }
}
