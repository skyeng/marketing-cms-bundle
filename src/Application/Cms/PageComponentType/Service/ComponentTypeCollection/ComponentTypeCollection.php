<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\PageComponentType\Service\ComponentTypeCollection;

use Skyeng\MarketingCmsBundle\Application\Cms\PageComponentType\ComponentType\ComponentTypeInterface;

class ComponentTypeCollection implements ComponentTypeCollectionInterface
{
    /**
     * @var ComponentTypeInterface[]
     */
    private $componentTypes;

    /**
     * @param ComponentTypeInterface[] $componentTypes
     */
    public function __construct(array $componentTypes = [])
    {
        foreach ($componentTypes as $componentType) {
            $this->componentTypes[$componentType->getName()] = $componentType;
        }
    }

    public function getComponentTypes(): array
    {
        return $this->componentTypes;
    }

    public function getByName(string $name): ?ComponentTypeInterface
    {
        return $this->componentTypes[$name] ?? null;
    }
}
