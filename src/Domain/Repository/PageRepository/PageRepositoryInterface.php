<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Repository\PageRepository;

use Skyeng\MarketingCmsBundle\Domain\Entity\Page;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageRepository\Exception\PageNotFoundException;
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

    /**
     * @param string $uri
     * @return Page
     * @throws PageRepositoryException
     * @throws PageNotFoundException
     */
    public function getByUri(string $uri, ?bool $published = null): Page;
}
