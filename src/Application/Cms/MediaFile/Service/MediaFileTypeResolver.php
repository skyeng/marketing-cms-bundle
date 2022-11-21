<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\MediaFile\Service;

use Skyeng\MarketingCmsBundle\Application\Exception\ApplicationException;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\MediaFileType;
use Symfony\Component\HttpFoundation\File\File;

class MediaFileTypeResolver
{
    private const IMAGE_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/gif',
    ];

    private const PDF_MIME_TYPE = 'application/pdf';

    private const VIDEO_MIME_TYPES = [
        'video/mp4',
    ];

    public function getMediaFileTypeByFile(File $file): MediaFileType
    {
        $mimeType = $file->getMimeType();

        if ($mimeType === self::PDF_MIME_TYPE) {
            return new MediaFileType(MediaFileType::PDF_TYPE);
        }

        if (in_array($mimeType, self::IMAGE_MIME_TYPES, true)) {
            return new MediaFileType(MediaFileType::IMAGE_TYPE);
        }

        if (in_array($mimeType, self::VIDEO_MIME_TYPES, true)) {
            return new MediaFileType(MediaFileType::VIDEO_TYPE);
        }

        throw new ApplicationException('Mime type ' . $mimeType . ' is not supported');
    }
}
