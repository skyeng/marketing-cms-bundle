<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Factory\TemplateComponent;

use Skyeng\MarketingCmsBundle\Domain\Entity\Template;
use Skyeng\MarketingCmsBundle\Domain\Entity\TemplateComponent;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\ComponentName;

interface TemplateComponentFactoryInterface
{
    public function create(
        Template $template,
        ComponentName $name,
        array $data,
        int $order,
        bool $isPublished
    ): TemplateComponent;
}
