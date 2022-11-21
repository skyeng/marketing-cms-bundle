<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PageOpenGraphDataFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public $tableName = 'cms_page_open_graph_data';

    public $dataFile = 'tests/fixtures/page-open-graph-data.php';

    public function getDependencies(): array
    {
        return [
            PageFixtures::class,
        ];
    }
}
