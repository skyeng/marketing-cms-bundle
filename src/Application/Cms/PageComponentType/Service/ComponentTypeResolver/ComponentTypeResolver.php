<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\PageComponentType\Service\ComponentTypeResolver;

use Skyeng\MarketingCmsBundle\Application\Cms\PageComponentType\ComponentType\ComponentTypeInterface;
use Skyeng\MarketingCmsBundle\Application\Cms\PageComponentType\Service\ComponentTypeCollection\ComponentTypeCollectionInterface;
use Skyeng\MarketingCmsBundle\Application\Cms\PageComponentType\Service\ComponentTypeResolver\Exception\ComponentTypeNotFoundException;

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
        $componentType = $this->collection->getByName($name);

        if ($componentType) {
            return $componentType;
        }

        throw new ComponentTypeNotFoundException();
    }
}
