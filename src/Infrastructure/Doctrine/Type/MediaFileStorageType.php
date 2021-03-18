<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Type;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\MediaFileStorage;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class MediaFileStorageType extends Type
{
    public function convertToPHPValue($value, AbstractPlatform $platform): ?MediaFileStorage
    {
        $result = parent::convertToPHPValue($value, $platform);
        return $result === null ? null : new MediaFileStorage((string)$result);
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    public function getName(): string
    {
        return 'cms_media_file_storage';
    }
}
