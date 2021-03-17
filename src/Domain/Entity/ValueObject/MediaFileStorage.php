<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject;

use Skyeng\MarketingCmsBundle\Domain\Traits\Exception\IncorrectValueObjectException;
use Skyeng\MarketingCmsBundle\Domain\Traits\ValueObjectTrait;

class MediaFileStorage
{
    public const S3_STORAGE = 's3';
    public const LOCAL_STORAGE = 'local';

    public const AVAILABLE_STORAGES = [
        self::S3_STORAGE => self::S3_STORAGE,
        self::LOCAL_STORAGE => self::LOCAL_STORAGE,
    ];

    use ValueObjectTrait;

    protected function checkValue(string $value): void
    {
        if (!in_array($value, self::AVAILABLE_STORAGES)) {
            throw new IncorrectValueObjectException('This media file storage is not supported');
        }
    }

    public function isS3(): bool
    {
        return $this->value === self::S3_STORAGE;
    }

    public function isLocal(): bool
    {
        return $this->value === self::LOCAL_STORAGE;
    }
}
