<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Type;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Uri;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class UriType extends Type
{
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Uri
    {
        $result = parent::convertToPHPValue($value, $platform);
        return $result === null ? null : new Uri((string)$result);
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getVarcharTypeDeclarationSQL($column);
    }

    public function getName(): string
    {
        return 'uri';
    }
}
