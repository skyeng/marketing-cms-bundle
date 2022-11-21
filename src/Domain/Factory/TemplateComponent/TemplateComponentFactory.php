<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Factory\TemplateComponent;

use Skyeng\MarketingCmsBundle\Domain\Entity\Template;
use Skyeng\MarketingCmsBundle\Domain\Entity\TemplateComponent;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\ComponentName;
use Skyeng\MarketingCmsBundle\Domain\Repository\ComponentRepository\ComponentRepositoryInterface;

class TemplateComponentFactory implements TemplateComponentFactoryInterface
{
    public function __construct(private ComponentRepositoryInterface $componentRepository)
    {
    }

    public function create(
        Template $template,
        ComponentName $name,
        array $data,
        int $order,
        bool $isPublished
    ): TemplateComponent {
        $component = new TemplateComponent(
            $this->componentRepository->getNextIdentity(),
            $template,
            $name,
            $data,
            $order,
        );

        $component->setIsPublished($isPublished);

        return $component;
    }
}
