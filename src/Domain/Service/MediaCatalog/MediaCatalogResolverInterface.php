<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\MediaCatalog;

use Skyeng\MarketingCmsBundle\Domain\Entity\MediaCatalog;

interface MediaCatalogResolverInterface
{
    public function getCatalogForEditor(): MediaCatalog;
}
