<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RedirectFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public string $tableName = 'cms_redirect';
    public string $dataFile = 'tests/fixtures/redirects.php';

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
