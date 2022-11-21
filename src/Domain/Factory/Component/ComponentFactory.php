<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Factory\Component;

use Skyeng\MarketingCmsBundle\Domain\Entity\Component;
use Skyeng\MarketingCmsBundle\Domain\Entity\Field;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\ComponentName;
use Skyeng\MarketingCmsBundle\Domain\Repository\ComponentRepository\ComponentRepositoryInterface;

class ComponentFactory implements ComponentFactoryInterface
{
    public function __construct(private ComponentRepositoryInterface $componentRepository)
    {
    }

    public function create(
        Field $field,
        ComponentName $name,
        array $data,
        int $order,
        bool $isPublished
    ): Component {
        $component = new Component(
            $this->componentRepository->getNextIdentity(),
            $field,
            $name,
            $data,
            $order,
        );

        $component->setIsPublished($isPublished);

        return $component;
    }
}
