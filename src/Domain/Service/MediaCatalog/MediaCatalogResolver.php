<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\MediaCatalog;

use Skyeng\MarketingCmsBundle\Domain\Entity\MediaCatalog;
use Skyeng\MarketingCmsBundle\Domain\Factory\MediaCatalog\MediaCatalogFactoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\MediaCatalogRepository\Exception\MediaCatalogNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Repository\MediaCatalogRepository\MediaCatalogRepositoryInterface;

class MediaCatalogResolver implements MediaCatalogResolverInterface
{
    private const CATALOG_EDITOR_NAME = 'Cms Editor';

    public function __construct(
        private MediaCatalogRepositoryInterface $mediaCatalogRepository,
        private MediaCatalogFactoryInterface $mediaCatalogFactory
    ) {
    }

    public function getCatalogForEditor(): MediaCatalog
    {
        try {
            $mediaCatalog = $this->mediaCatalogRepository->getByName(self::CATALOG_EDITOR_NAME);
        } catch (MediaCatalogNotFoundException) {
            $mediaCatalog = $this->mediaCatalogFactory->create(self::CATALOG_EDITOR_NAME);
            $this->mediaCatalogRepository->save($mediaCatalog);
        }

        return $mediaCatalog;
    }
}
