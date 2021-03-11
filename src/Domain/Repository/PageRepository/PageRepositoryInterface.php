<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Repository\PageRepository;

use Skyeng\MarketingCmsBundle\Domain\Entity\Page;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageRepository\Exception\PageRepositoryException;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

interface PageRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @return Page[]
     * @throws PageRepositoryException
     */
    public function getAll(): array;
}
