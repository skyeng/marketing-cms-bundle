<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Ramsey\Uuid\Uuid;
use Skyeng\MarketingCmsBundle\Domain\Entity\MediaCatalog;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Repository\MediaCatalogRepository\Exception\MediaCatalogNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Repository\MediaCatalogRepository\Exception\MediaCatalogRepositoryException;
use Skyeng\MarketingCmsBundle\Domain\Repository\MediaCatalogRepository\MediaCatalogRepositoryInterface;

class MediaCatalogRepository extends ServiceEntityRepository implements MediaCatalogRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaCatalog::class);
    }

    public function getNextIdentity(): Id
    {
        $uuid = Uuid::uuid4();
        return new Id($uuid->toString());
    }

    public function getAll(): array
    {
        try {
            return $this->findAll();
        } catch (Exception $e) {
            throw new MediaCatalogRepositoryException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getFirst(): MediaCatalog
    {
        try {
            $catalog = $this->findOneBy([], ['id' => 'desc']);

            if ($catalog === null) {
                throw new MediaCatalogNotFoundException();
            }

            return $catalog;
        } catch (MediaCatalogNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new MediaCatalogRepositoryException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
