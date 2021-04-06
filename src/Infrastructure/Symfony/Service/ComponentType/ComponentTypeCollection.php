<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Service\ComponentType;

use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\ComponentTypes\ComponentTypeInterface;

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
}
