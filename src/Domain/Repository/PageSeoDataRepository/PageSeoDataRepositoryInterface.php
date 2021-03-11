<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Repository\PageSeoDataRepository;

use Skyeng\MarketingCmsBundle\Domain\Entity\Page;
use Skyeng\MarketingCmsBundle\Domain\Entity\PageSeoData;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageSeoDataRepository\Exception\PageSeoDataNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageSeoDataRepository\Exception\PageSeoDataRepositoryException;

interface PageSeoDataRepositoryInterface
{
    /**
     * @return PageSeoData
     * @throws PageSeoDataNotFoundException
     * @throws PageSeoDataRepositoryException
     */
    public function getByPage(Page $page): PageSeoData;
}
