<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Factory\MediaCatalog;

use Skyeng\MarketingCmsBundle\Domain\Entity\MediaCatalog;

interface MediaCatalogFactoryInterface
{
    public function create(string $title): MediaCatalog;
}
