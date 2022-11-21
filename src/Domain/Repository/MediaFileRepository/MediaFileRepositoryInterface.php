<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Repository\MediaFileRepository;

use Skyeng\MarketingCmsBundle\Domain\Entity\MediaCatalog;
use Skyeng\MarketingCmsBundle\Domain\Entity\MediaFile;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Repository\MediaFileRepository\Exception\MediaFileRepositoryException;

interface MediaFileRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @return MediaFile[]
     *
     * @throws MediaFileRepositoryException
     */
    public function getByCatalog(MediaCatalog $catalog): array;

    /**
     * @throws MediaFileRepositoryException
     */
    public function remove(MediaFile ...$mediaFiles): void;

    /**
     * @param string[] $ids
     *
     * @return MediaFile[]
     *
     * @throws MediaFileRepositoryException
     */
    public function getByIds(array $ids): array;

    /**
     * @throws MediaFileRepositoryException
     */
    public function save(MediaFile ...$mediaFiles): void;
}
