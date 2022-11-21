<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\DataFixtures\HeadlessCms\Localization;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Skyeng\MarketingCmsBundle\Tests\DataFixtures\AbstractFixture;

class FieldFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'cms_field';
    public string $dataFile = 'tests/fixtures/headless-cms/localization/fields.php';

    /**
     * {@inheritDoc}
     */
    public function getDependencies(): array
    {
        return [
            ModelFixtures::class,
        ];
    }
}
