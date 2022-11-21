<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Helper;

use Codeception\Module;
use Skyeng\MarketingCmsBundle\Tests\FixturesLoaderTrait;

class Api extends Module
{
    use ContainerTrait;
    use FixturesLoaderTrait;
}
