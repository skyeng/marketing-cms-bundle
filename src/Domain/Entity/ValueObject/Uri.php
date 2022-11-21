<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject;

use Skyeng\MarketingCmsBundle\Domain\Traits\Exception\IncorrectValueObjectException;
use Skyeng\MarketingCmsBundle\Domain\Traits\ValueObjectTrait;

class Uri
{
    use ValueObjectTrait;
    public const PATH_PATTERN = '~^/.{1,}$~';

    protected function checkValue(string $value): void
    {
        if (!preg_match(self::PATH_PATTERN, $value)) {
            throw new IncorrectValueObjectException('Uri path must start with / and contain simple characters, /, -, or.');
        }
    }

    public function getPathname(): string
    {
        return mb_substr($this->value, 1);
    }

    public function getFileName(): string
    {
        return basename($this->value);
    }
}
