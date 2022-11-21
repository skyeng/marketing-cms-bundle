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
        'image/svg+xml',
        'image/svg',
        'image/webp',
    ];

    private const PDF_MIME_TYPE = 'application/pdf';

    private const VIDEO_MIME_TYPES = [
        'video/mp4',
        'video/mpeg',
        'video/x-flv',
        'video/x-msvideo',
    ];

    private const ACRCHIVE_MIME_TYPES = [
        'application/x-rar-compressed',
        'application/zip',
    ];

    private const TEXT_MIME_TYPES = [
        'text/csv',
        'text/xml',
        'text/plain',
        'text/markdown',
    ];

    private const AUDIO_MIME_TYPES = [
        'audio/mp4',
        'audio/aac',
        'audio/mpeg',
    ];

    private const VND_MIME_TYPES = [
        'application/vnd.oasis.opendocument.text',
        'application/vnd.oasis.opendocument.spreadsheet',
        'application/vnd.oasis.opendocument.presentation',
        'application/vnd.oasis.opendocument.graphics',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-excel.sheet.macroEnabled.12',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.mozilla.xul+xml',
        'application/vnd.google-earth.kml+xml',
    ];

    /**
     * @throws ApplicationException
     */
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

        if (in_array($mimeType, self::AUDIO_MIME_TYPES, true)) {
            return new MediaFileType(MediaFileType::AUDIO_TYPE);
        }

        if (in_array($mimeType, self::TEXT_MIME_TYPES, true)) {
            return new MediaFileType(MediaFileType::TEXT_TYPE);
        }

        if (in_array($mimeType, self::ACRCHIVE_MIME_TYPES, true)) {
            return new MediaFileType(MediaFileType::ARCHIVE_TYPE);
        }

        if (in_array($mimeType, self::VND_MIME_TYPES, true)) {
            return new MediaFileType(MediaFileType::VND_TYPE);
        }

        throw new ApplicationException('Mime type '.$mimeType.' is not supported');
    }
}
