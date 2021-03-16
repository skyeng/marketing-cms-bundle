<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject;

use Skyeng\MarketingCmsBundle\Domain\Traits\Exception\IncorrectValueObjectException;
use Skyeng\MarketingCmsBundle\Domain\Traits\ValueObjectTrait;

class PageComponentName
{
    public const HTML_COMPONENT = 'html-component';

    public const AVAILABLE_COMPONENTS = [
        self::HTML_COMPONENT => self::HTML_COMPONENT,
    ];

    use ValueObjectTrait;

    protected function checkValue(string $value): void
    {
        if (!in_array($value, self::AVAILABLE_COMPONENTS)) {
            throw new IncorrectValueObjectException('This component name is not supported');
        }
    }

    public function isHtml(): bool
    {
        return $this->value === self::HTML_COMPONENT;
    }
}
