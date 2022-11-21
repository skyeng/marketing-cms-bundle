<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Factory\Component;

use Skyeng\MarketingCmsBundle\Domain\Entity\Component;
use Skyeng\MarketingCmsBundle\Domain\Entity\Field;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\ComponentName;

interface ComponentFactoryInterface
{
    public function create(
        Field $field,
        ComponentName $name,
        array $data,
        int $order,
        bool $isPublished
    ): Component;
}
