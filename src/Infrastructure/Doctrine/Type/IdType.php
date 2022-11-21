<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Ramsey\Uuid\Doctrine\UuidType as BaseUuidType;
use Ramsey\Uuid\UuidInterface;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

class IdType extends BaseUuidType
{
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return !$platform->hasNativeGuidType();
    }

    /** @psalm-suppress InvalidReturnType */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Id
    {
        $result = parent::convertToPHPValue($value, $platform);

        /** @psalm-suppress InvalidReturnStatement */
        return $result instanceof UuidInterface ? new Id((string) $result) : null;
    }
}
