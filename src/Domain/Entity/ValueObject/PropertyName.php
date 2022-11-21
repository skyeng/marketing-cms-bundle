<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject;

use Skyeng\MarketingCmsBundle\Domain\Traits\Exception\IncorrectValueObjectException;

class PropertyName
{
    public const SEPARATOR = '-';

    public string $type;
    public ?string $locale;
    public string $propertyName;

    public function __construct(string $propertyName)
    {
        $propertyNameInfo = $this->decodePropertyName($propertyName);

        if (count($propertyNameInfo) < 3) {
            throw new IncorrectValueObjectException('The property name mast contains info about the type and locale, for example Text-en-property_name');
        }

        $type = $propertyNameInfo[0] ?: '';
        $this->locale = $propertyNameInfo[1] ?: null;
        $propertyName = $propertyNameInfo[2] ?: '';

        if ($type === '') {
            throw new IncorrectValueObjectException('Property type should not be empty');
        }

        if ($propertyName === '') {
            throw new IncorrectValueObjectException('Property name should not be empty');
        }

        $this->type = $type;
        $this->propertyName = $propertyName;
    }

    /**
     * @return string[]
     */
    private function decodePropertyName(string $propertyName): array
    {
        return explode(self::SEPARATOR, $propertyName);
    }
}
