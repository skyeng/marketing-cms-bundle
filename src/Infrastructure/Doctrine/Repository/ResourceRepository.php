<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Skyeng\MarketingCmsBundle\Domain\Entity\Resource as ResourceEntity;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Repository\ResourceRepository\Exception\ResourceNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Repository\ResourceRepository\Exception\ResourceRepositoryException;
use Skyeng\MarketingCmsBundle\Domain\Repository\ResourceRepository\ResourceRepositoryInterface;

class ResourceRepository extends ServiceEntityRepository implements ResourceRepositoryInterface
{
    use LoggerAwareTrait;

    public function __construct(
        ManagerRegistry $registry,
        LoggerInterface $logger
    ) {
        parent::__construct($registry, ResourceEntity::class);
        $this->logger = $logger;
    }

    public function getNextIdentity(): Id
    {
        $uuid = Uuid::uuid4();

        return new Id($uuid->toString());
    }

    /**
     * {@inheritDoc}
     */
    public function getAll(): array
    {
        try {
            /** @var ResourceEntity[] $resources */
            $resources = $this->getAll();
        } catch (Exception $e) {
            throw new ResourceRepositoryException($e->getMessage(), $e->getCode(), $e);
        }

        return $resources;
    }

    /**
     * {@inheritDoc}
     */
    public function getByUri(string $uri): ResourceEntity
    {
        try {
            $resource = $this->findOneBy(['uri' => $uri]);
        } catch (Exception $e) {
            throw new ResourceRepositoryException($e->getMessage(), $e->getCode(), $e);
        }

        if (!$resource instanceof ResourceEntity) {
            throw new ResourceNotFoundException();
        }

        return $resource;
    }

    public function save(ResourceEntity $resource): void
    {
        $this->getEntityManager()->persist($resource);
        $this->getEntityManager()->flush();
    }
}
