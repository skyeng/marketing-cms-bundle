<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\PageComponentType\Service\ComponentTypeResolver;

use Skyeng\MarketingCmsBundle\Application\Cms\PageComponentType\ComponentType\ComponentTypeInterface;

interface ComponentTypeResolverInterface
{
    public function resolveByName(string $name): ComponentTypeInterface;
}
