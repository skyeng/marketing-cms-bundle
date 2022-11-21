<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Type;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\ResourceType as ResourceTypeEntity;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class ResourceType extends Type
{
    public function convertToPHPValue($value, AbstractPlatform $platform): ?ResourceTypeEntity
    {
        $result = parent::convertToPHPValue($value, $platform);
        return $result === null ? null : new ResourceTypeEntity((string)$result);
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getVarcharTypeDeclarationSQL($column);
    }

    public function getName(): string
    {
        return 'resource_type';
    }
}
