<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject;

use Skyeng\MarketingCmsBundle\Domain\Traits\Exception\IncorrectValueObjectException;
use Skyeng\MarketingCmsBundle\Domain\Traits\ValueObjectTrait;

class MediaFileType
{
    public const IMAGE_TYPE = 'image';
    public const PDF_TYPE = 'pdf';
    public const VIDEO_TYPE = 'video';

    public const AVAILABLE_TYPES = [
        self::IMAGE_TYPE => self::IMAGE_TYPE,
        self::PDF_TYPE => self::PDF_TYPE,
        self::VIDEO_TYPE => self::VIDEO_TYPE,
    ];

    use ValueObjectTrait;

    protected function checkValue(string $value): void
    {
        if (!in_array($value, self::AVAILABLE_TYPES)) {
            throw new IncorrectValueObjectException('This media file type is not supported');
        }
    }

    public function isPdf(): bool
    {
        return $this->value === self::PDF_TYPE;
    }

    public function isImage(): bool
    {
        return $this->value === self::IMAGE_TYPE;
    }

    public function isVideo(): bool
    {
        return $this->value === self::VIDEO_TYPE;
    }
}
