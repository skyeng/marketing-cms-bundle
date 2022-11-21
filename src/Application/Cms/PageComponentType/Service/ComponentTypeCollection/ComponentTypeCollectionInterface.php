<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\PageComponentType\Service\ComponentTypeCollection;

use Skyeng\MarketingCmsBundle\Application\Cms\PageComponentType\ComponentType\ComponentTypeInterface;

interface ComponentTypeCollectionInterface
{
    /**
     * @return ComponentTypeInterface[]
     */
    public function getComponentTypes(): array;

    public function getByName(string $name): ?ComponentTypeInterface;
}
