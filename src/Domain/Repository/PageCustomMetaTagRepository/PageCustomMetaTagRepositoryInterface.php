<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Repository\PageCustomMetaTagRepository;

use Skyeng\MarketingCmsBundle\Domain\Entity\Page;
use Skyeng\MarketingCmsBundle\Domain\Entity\PageCustomMetaTag;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageCustomMetaTagRepository\Exception\PageCustomMetaTagRepositoryException;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

interface PageCustomMetaTagRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @return PageCustomMetaTag[]
     * @throws PageCustomMetaTagRepositoryException
     */
    public function getByPage(Page $page): array;
}
