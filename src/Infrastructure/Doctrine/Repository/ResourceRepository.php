<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Repository;

use Skyeng\MarketingCmsBundle\Domain\Entity\Resource as ResourceEntity;
use Skyeng\MarketingCmsBundle\Domain\Repository\ResourceRepository\Exception\ResourceNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Repository\ResourceRepository\Exception\ResourceRepositoryException;
use Skyeng\MarketingCmsBundle\Domain\Repository\ResourceRepository\ResourceRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

class ResourceRepository extends ServiceEntityRepository implements ResourceRepositoryInterface
{
    use LoggerAwareTrait;

    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, ResourceEntity::class);
        $this->logger = $logger;
    }

    public function getNextIdentity(): Id
    {
        $uuid = Uuid::uuid4();
        return new Id($uuid->toString());
    }

    public function getAll(): array
    {
        try {
            return $this->findBy([]);
        } catch (Exception $e) {
            throw new ResourceRepositoryException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getByUri(string $uri): ResourceEntity
    {
        try {
            /** @var ResourceEntity $resource */
            $resource = $this->findOneBy(['uri' => $uri,]);

            if ($resource === null) {
                throw new ResourceNotFoundException();
            }

            return $resource;
        } catch (ResourceNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new ResourceRepositoryException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function save(ResourceEntity $resource): void
    {
        $this->getEntityManager()->persist($resource);
        $this->getEntityManager()->flush($resource);
    }
}
