<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Repository\MediaCatalogRepository;

use Skyeng\MarketingCmsBundle\Domain\Entity\MediaCatalog;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Repository\MediaCatalogRepository\Exception\MediaFileRepositoryException;

interface MediaCatalogRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @return MediaCatalog[]
     * @throws MediaFileRepositoryException
     */
    public function getAll(): array;
}
