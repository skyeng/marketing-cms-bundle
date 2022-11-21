<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\DataFixtures;

class TemplateFixtures extends AbstractFixture
{
    public string $tableName = 'cms_template';
    public string $dataFile = 'tests/fixtures/templates.php';
}
