<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Repository\PageOpenGraphDataRepository;

use Skyeng\MarketingCmsBundle\Domain\Entity\Page;
use Skyeng\MarketingCmsBundle\Domain\Entity\PageOpenGraphData;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageOpenGraphDataRepository\Exception\PageOpenGraphDataNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageOpenGraphDataRepository\Exception\PageOpenGraphDataRepositoryException;

interface PageOpenGraphDataRepositoryInterface
{
    /**
     * @return PageOpenGraphData
     * @throws PageOpenGraphDataNotFoundException
     * @throws PageOpenGraphDataRepositoryException
     */
    public function getByPage(Page $page): PageOpenGraphData;
}
