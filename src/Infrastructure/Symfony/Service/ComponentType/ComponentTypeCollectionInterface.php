<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Service\ComponentType;

use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\ComponentTypes\ComponentTypeInterface;

interface ComponentTypeCollectionInterface
{
    /**
     * @return ComponentTypeInterface[]
     */
    public function getComponentTypes(): array;
}
