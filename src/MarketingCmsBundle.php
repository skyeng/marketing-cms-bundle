<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle;

use Skyeng\MarketingCmsBundle\Infrastructure\DependencyInjection\MarketingCmsExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MarketingCmsBundle extends Bundle
{
    protected function getContainerExtensionClass(): string
    {
        return MarketingCmsExtension::class;
    }
}
