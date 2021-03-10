<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Type;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\CacheTime;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class CacheTimeType extends Type
{
    public function convertToPHPValue($value, AbstractPlatform $platform): ?CacheTime
    {
        $result = parent::convertToPHPValue($value, $platform);
        return $result === null ? null : new CacheTime((string)$result);
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getVarcharTypeDeclarationSQL($column);
    }

    public function getName(): string
    {
        return 'cache_time';
    }
}
