<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TemplateComponentFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'cms_template_component';
    public string $dataFile = 'tests/fixtures/template-components.php';

    /**
     * {@inheritDoc}
     */
    public function getDependencies(): array
    {
        return [
            TemplateFixtures::class,
        ];
    }
}
