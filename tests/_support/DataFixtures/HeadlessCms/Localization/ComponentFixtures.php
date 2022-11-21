<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\DataFixtures\HeadlessCms\Localization;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Skyeng\MarketingCmsBundle\Tests\DataFixtures\AbstractFixture;

class ComponentFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'cms_component';
    public string $dataFile = 'tests/fixtures/headless-cms/localization/components.php';

    /**
     * {@inheritDoc}
     */
    public function getDependencies(): array
    {
        return [
            FieldFixtures::class,
        ];
    }
}
