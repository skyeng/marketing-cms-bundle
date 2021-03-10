<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject;

use Skyeng\MarketingCmsBundle\Domain\Traits\Exception\IncorrectValueObjectException;

class Id
{
    /**
     * @var string
     */
    protected $value;

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

    public function isEqual(Id $id): bool
    {
        return $this->value === (string)$id;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
