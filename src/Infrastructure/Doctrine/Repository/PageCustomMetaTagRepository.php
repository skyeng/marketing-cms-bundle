<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Repository;

use Skyeng\MarketingCmsBundle\Domain\Entity\Page;
use Skyeng\MarketingCmsBundle\Domain\Entity\PageCustomMetaTag;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageCustomMetaTagRepository\Exception\PageCustomMetaTagRepositoryException;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageCustomMetaTagRepository\PageCustomMetaTagRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Ramsey\Uuid\Uuid;

class PageCustomMetaTagRepository extends ServiceEntityRepository implements PageCustomMetaTagRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PageCustomMetaTag::class);
    }

    public function getNextIdentity(): Id
    {
        $uuid = Uuid::uuid4();
        return new Id($uuid->toString());
    }

    public function getByPage(Page $page): array
    {
        try {
            return $this->findBy(['page' => $page]);
        } catch (Exception $e) {
            throw new PageCustomMetaTagRepositoryException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
