<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Factory\MediaCatalog;

use Skyeng\MarketingCmsBundle\Domain\Entity\MediaCatalog;
use Skyeng\MarketingCmsBundle\Domain\Repository\MediaCatalogRepository\MediaCatalogRepositoryInterface;

class MediaCatalogFactory implements MediaCatalogFactoryInterface
{
    public function __construct(private MediaCatalogRepositoryInterface $mediaCatalogRepository)
    {
    }

    public function create(string $title): MediaCatalog
    {
        return new MediaCatalog(
            $this->mediaCatalogRepository->getNextIdentity(),
            $title,
        );
    }
}
