<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Traits;

trait ValueObjectTrait
{
    /**
     * @var string
     */
    protected $value;

    public function __construct(string $value)
    {
        $this->checkValue($value);
        $this->value = $value;
    }

    public function __toString()
    {
        return (string) $this->value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isEqual(self $valueObject): bool
    {
        return $this->getValue() === $valueObject->getValue();
    }

    protected function checkValue(string $value): void
    {
    }
}
