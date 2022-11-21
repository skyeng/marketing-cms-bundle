<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Factory\MediaFile;

use DateTimeInterface;
use Skyeng\MarketingCmsBundle\Domain\Entity\MediaCatalog;
use Skyeng\MarketingCmsBundle\Domain\Entity\MediaFile;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\MediaFileStorage;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\MediaFileType;
use Symfony\Component\HttpFoundation\File\File;

interface MediaFileFactoryInterface
{
    public function create(
        MediaCatalog $mediaCatalog,
        string $title,
        ?File $file = null,
        ?MediaFileType $mediaFileType = null,
        ?MediaFileStorage $mediaFileStorage = null,
        ?string $name = null,
        ?string $originalName = null,
        ?DateTimeInterface $createdAt = null
    ): MediaFile;
}
