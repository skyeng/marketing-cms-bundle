<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject;

use Skyeng\MarketingCmsBundle\Domain\Traits\Exception\IncorrectValueObjectException;
use Skyeng\MarketingCmsBundle\Domain\Traits\ValueObjectTrait;

class ResourceType
{
    public const FILE_TYPE = 'file';
    public const REDIRECT_TYPE = 'redirect';

    public const AVAILABLE_TYPES = [
        self::FILE_TYPE => self::FILE_TYPE,
        self::REDIRECT_TYPE => self::REDIRECT_TYPE,
    ];

    use ValueObjectTrait;

    protected function checkValue(string $value): void
    {
        if (!in_array($value, self::AVAILABLE_TYPES)) {
            throw new IncorrectValueObjectException('This resource type is not supported');
        }
    }

    public function isFile(): bool
    {
        return $this->value === self::FILE_TYPE;
    }

    public function isRedirect(): bool
    {
        return $this->value === self::REDIRECT_TYPE;
    }
}