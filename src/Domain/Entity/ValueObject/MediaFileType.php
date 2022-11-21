<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject;

use Skyeng\MarketingCmsBundle\Domain\Traits\Exception\IncorrectValueObjectException;
use Skyeng\MarketingCmsBundle\Domain\Traits\ValueObjectTrait;

class MediaFileType
{
    use ValueObjectTrait;
    public const IMAGE_TYPE = 'image';
    public const PDF_TYPE = 'pdf';
    public const VIDEO_TYPE = 'video';
    public const AUDIO_TYPE = 'audio';
    public const TEXT_TYPE = 'text';
    public const ARCHIVE_TYPE = 'archive';
    public const VND_TYPE = 'vnd';

    public const AVAILABLE_TYPES = [
        self::IMAGE_TYPE => self::IMAGE_TYPE,
        self::PDF_TYPE => self::PDF_TYPE,
        self::VIDEO_TYPE => self::VIDEO_TYPE,
        self::AUDIO_TYPE => self::AUDIO_TYPE,
        self::TEXT_TYPE => self::TEXT_TYPE,
        self::ARCHIVE_TYPE => self::ARCHIVE_TYPE,
        self::VND_TYPE => self::VND_TYPE,
    ];

    protected function checkValue(string $value): void
    {
        if (!in_array($value, self::AVAILABLE_TYPES, true)) {
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

    public function isAudio(): bool
    {
        return $this->value === self::AUDIO_TYPE;
    }

    public function isText(): bool
    {
        return $this->value === self::TEXT_TYPE;
    }

    public function isArchive(): bool
    {
        return $this->value === self::ARCHIVE_TYPE;
    }

    public function isVnd(): bool
    {
        return $this->value === self::VND_TYPE;
    }
}
