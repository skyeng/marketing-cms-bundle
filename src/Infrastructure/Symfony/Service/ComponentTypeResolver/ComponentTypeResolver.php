<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Service\ComponentTypeResolver;

use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\ComponentTypes\ComponentTypeInterface;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Service\ComponentType\ComponentTypeCollectionInterface;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Service\ComponentTypeResolver\Exception\ComponentTypeNotFoundException;

class ComponentTypeResolver implements ComponentTypeResolverInterface
{
    /**
     * @var ComponentTypeCollectionInterface
     */
    private $collection;

    public function __construct(ComponentTypeCollectionInterface $collection)
    {
        $this->collection = $collection;
    }

    public function resolveByName(string $name): ComponentTypeInterface
    {
        foreach ($this->collection->getComponentTypes() as $componentType) {
            if ($componentType->getName() === $name) {
                return $componentType;
            }
        }

        throw new ComponentTypeNotFoundException();
    }
}
