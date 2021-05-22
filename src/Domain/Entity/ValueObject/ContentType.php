<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject;

use Skyeng\MarketingCmsBundle\Domain\Traits\Exception\IncorrectValueObjectException;
use Skyeng\MarketingCmsBundle\Domain\Traits\ValueObjectTrait;

class ContentType
{
    public const TEXT_TYPE = 'text/plain';
    public const JSON_TYPE = 'application/json';
    public const HTML_TYPE = 'text/html';
    public const XML_TYPE = 'text/xml';

    public const AVAILABLE_TYPES = [
        self::HTML_TYPE => self::HTML_TYPE,
        self::TEXT_TYPE => self::TEXT_TYPE,
        self::XML_TYPE => self::XML_TYPE,
        self::JSON_TYPE => self::JSON_TYPE,
    ];

    use ValueObjectTrait;

    protected function checkValue(string $value): void
    {
        if (!in_array($value, self::AVAILABLE_TYPES)) {
            throw new IncorrectValueObjectException('This content type is not supported');
        }
    }

    public function isTextType(): bool
    {
        return $this->value === self::TEXT_TYPE;
    }

    public function isJsonType(): bool
    {
        return $this->value === self::JSON_TYPE;
    }

    public function isHtmlType(): bool
    {
        return $this->value === self::HTML_TYPE;
    }

    public function isXMLType(): bool
    {
        return $this->value === self::XML_TYPE;
    }
}
