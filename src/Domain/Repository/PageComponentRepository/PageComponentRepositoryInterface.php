<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Repository\PageComponentRepository;

use Skyeng\MarketingCmsBundle\Domain\Entity\Page;
use Skyeng\MarketingCmsBundle\Domain\Entity\PageComponent;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageComponentRepository\Exception\PageComponentRepositoryException;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

interface PageComponentRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @return PageComponent[]
     * @throws PageComponentRepositoryException
     */
    public function getByPage(Page $page): array;
}
