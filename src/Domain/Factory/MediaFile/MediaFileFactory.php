<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Factory\MediaFile;

use DateTimeImmutable;
use DateTimeInterface;
use Skyeng\MarketingCmsBundle\Domain\Entity\MediaCatalog;
use Skyeng\MarketingCmsBundle\Domain\Entity\MediaFile;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\MediaFileStorage;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\MediaFileType;
use Skyeng\MarketingCmsBundle\Domain\Repository\MediaCatalogRepository\MediaCatalogRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\MediaFileRepository\MediaFileRepositoryInterface;
use Symfony\Component\HttpFoundation\File\File;

class MediaFileFactory implements MediaFileFactoryInterface
{
    public function __construct(
        private MediaFileRepositoryInterface $mediaFileRepository,
        private MediaCatalogRepositoryInterface $mediaCatalogRepository
    ) {
    }

    public function create(
        ?MediaCatalog $mediaCatalog = null,
        ?string $title = null,
        ?File $file = null,
        ?MediaFileType $mediaFileType = null,
        ?MediaFileStorage $mediaFileStorage = null,
        ?string $name = null,
        ?string $originalName = null,
        ?DateTimeInterface $createdAt = null
    ): MediaFile {
        $mediaFile = new MediaFile(
            $this->mediaFileRepository->getNextIdentity(),
            $mediaCatalog ?? $this->mediaCatalogRepository->getFirst(),
            $title ?? '',
            $mediaFileType ?? new MediaFileType(MediaFileType::IMAGE_TYPE),
            $name ?? '',
            $mediaFileStorage ?? new MediaFileStorage(MediaFileStorage::NFS_STORAGE),
            $originalName ?? '',
            $createdAt ?? new DateTimeImmutable(),
        );

        if ($file !== null) {
            $mediaFile->setFile($file);
        }

        return $mediaFile;
    }
}
