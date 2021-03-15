<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PageCustomMetaTagFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public $tableName = 'cms_page_custom_meta_tag';

    public $dataFile = 'tests/fixtures/page-custom-meta-tags.php';

    public function getDependencies(): array
    {
        return [
            PageFixtures::class,
        ];
    }
}
