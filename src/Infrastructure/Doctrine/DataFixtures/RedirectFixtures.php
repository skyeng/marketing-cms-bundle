<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RedirectFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public $tableName = 'cms_redirect';

    public $dataFile = 'tests/fixtures/redirects.php';

    public function getDependencies(): array
    {
        return [
            ResourceFixtures::class,
        ];
    }
}
