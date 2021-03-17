<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject;

use Skyeng\MarketingCmsBundle\Domain\Traits\Exception\IncorrectValueObjectException;
use Skyeng\MarketingCmsBundle\Domain\Traits\ValueObjectTrait;

class MediaFileStorage
{
    public const S3_STORAGE = 'uploads.s3';
    public const NFS_STORAGE = 'uploads.nfs';

    public const AVAILABLE_STORAGES = [
        self::S3_STORAGE => self::S3_STORAGE,
        self::NFS_STORAGE => self::NFS_STORAGE,
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

    public function isNfs(): bool
    {
        return $this->value === self::NFS_STORAGE;
    }
}
