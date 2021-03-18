<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\MediaFile\Service;

use Skyeng\MarketingCmsBundle\Domain\Entity\MediaFile;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class MediaFilePathResolver
{
    private const IMAGE_HTML_MASK = '<img src="%s" title="%s"/>';
    private const VIDEO_HTML_MASK = '<video><source src="%s"></video>';
    private const PDF_HTML_MASK = '<embed src="%s"/>';

    /**
     * @var UploaderHelper
     */
    private $uploaderHelper;

    public function __construct(UploaderHelper $uploaderHelper)
    {
        $this->uploaderHelper = $uploaderHelper;
    }

    public function getFileUrl(MediaFile $file): ?string
    {
        return $this->uploaderHelper->asset($file, 'file');
    }

    public function getFileHtml(MediaFile $file): ?string
    {
        $url = $this->getFileUrl($file);

        if ($file->getType()->isImage()) {
            return sprintf(self::IMAGE_HTML_MASK, $url, $file->getTitle());
        }

        if ($file->getType()->isVideo()) {
            return sprintf(self::VIDEO_HTML_MASK, $url);
        }

        if ($file->getType()->isPdf()) {
            return sprintf(self::PDF_HTML_MASK, $url);
        }

        return null;
    }
}
