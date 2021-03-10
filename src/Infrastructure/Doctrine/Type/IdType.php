<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Type;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Ramsey\Uuid\Doctrine\UuidType as BaseUuidType;

class IdType extends BaseUuidType
{
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return !$platform->hasNativeGuidType();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $result = parent::convertToPHPValue($value, $platform);
        return $result === null ? null : new Id((string)$result);
    }
}
