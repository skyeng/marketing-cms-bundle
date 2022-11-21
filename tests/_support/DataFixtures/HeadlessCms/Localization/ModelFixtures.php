<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\DataFixtures\HeadlessCms\Localization;

use Skyeng\MarketingCmsBundle\Tests\DataFixtures\AbstractFixture;

class ModelFixtures extends AbstractFixture
{
    public string $tableName = 'cms_model';
    public string $dataFile = 'tests/fixtures/headless-cms/localization/models.php';
}
