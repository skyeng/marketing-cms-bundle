<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\MediaFileType as MediaFileTypeValueObject;

class MediaFileType extends Type
{
    public function convertToPHPValue($value, AbstractPlatform $platform): ?MediaFileTypeValueObject
    {
        $result = parent::convertToPHPValue($value, $platform);
        return $result === null ? null : new MediaFileTypeValueObject((string)$result);
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    public function getName(): string
    {
        return 'media_file_type';
    }
}
