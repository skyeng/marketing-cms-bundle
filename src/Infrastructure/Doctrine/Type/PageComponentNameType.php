<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Type;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\PageComponentName;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class PageComponentNameType extends Type
{
    public function convertToPHPValue($value, AbstractPlatform $platform): ?PageComponentName
    {
        $result = parent::convertToPHPValue($value, $platform);
        return $result === null ? null : new PageComponentName((string)$result);
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    public function getName(): string
    {
        return 'page_component_name';
    }
}
