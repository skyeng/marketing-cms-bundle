<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject;

use Skyeng\MarketingCmsBundle\Domain\Traits\ValueObjectTrait;

class ComponentName
{
    use ValueObjectTrait;
    public const TEMPLATE_NAME = 'template-component';

    public function isTemplateName(): bool
    {
        return $this->value === self::TEMPLATE_NAME;
    }
}
