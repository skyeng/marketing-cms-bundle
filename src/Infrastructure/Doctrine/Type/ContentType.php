<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\ContentType as ContentTypeEntity;

class ContentType extends Type
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value->getValue();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?ContentTypeEntity
    {
        $result = parent::convertToPHPValue($value, $platform);

        return $result === null ? null : new ContentTypeEntity((string) $result);
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getVarcharTypeDeclarationSQL($column);
    }

    public function getName(): string
    {
        return 'content_type';
    }
}
