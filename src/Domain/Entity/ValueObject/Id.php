<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject;

use Skyeng\MarketingCmsBundle\Domain\Traits\Exception\IncorrectValueObjectException;
use Stringable;

class Id implements Stringable
{
    protected string $value;

    public function __construct(string $value)
    {
        if (empty($value)) {
            throw new IncorrectValueObjectException('Id value must not be empty');
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function isEqual(self $id): bool
    {
        return $this->value === (string) $id;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
