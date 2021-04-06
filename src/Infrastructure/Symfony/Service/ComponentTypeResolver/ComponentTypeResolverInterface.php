<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Service\ComponentTypeResolver;

use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\ComponentTypes\ComponentTypeInterface;

interface ComponentTypeResolverInterface
{
    public function resolveByName(string $name): ComponentTypeInterface;
}
