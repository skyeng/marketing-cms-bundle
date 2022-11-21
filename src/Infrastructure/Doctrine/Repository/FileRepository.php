<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Skyeng\MarketingCmsBundle\Domain\Entity\File;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Repository\FileRepository\Exception\FileNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Repository\FileRepository\Exception\FileRepositoryException;
use Skyeng\MarketingCmsBundle\Domain\Repository\FileRepository\FileRepositoryInterface;

class FileRepository extends ServiceEntityRepository implements FileRepositoryInterface
{
    use LoggerAwareTrait;

    public function __construct(
        ManagerRegistry $registry,
        LoggerInterface $logger
    ) {
        parent::__construct($registry, File::class);
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
            /** @var File[] $files */
            $files = $this->findBy([]);
        } catch (Exception $e) {
            throw new FileRepositoryException($e->getMessage(), $e->getCode(), $e);
        }

        return $files;
    }

    /**
     * {@inheritDoc}
     */
    public function getByUri(string $uri): File
    {
        try {
            $file = $this->createQueryBuilder('f')
                ->innerJoin('f.resource', 'r')
                ->andWhere('r.uri = :uri')
                ->setParameter('uri', $uri)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (Exception $e) {
            throw new FileRepositoryException($e->getMessage(), $e->getCode(), $e);
        }

        if (!$file instanceof File) {
            throw new FileNotFoundException();
        }

        return $file;
    }

    public function save(File $file): void
    {
        $this->getEntityManager()->persist($file);
        $this->getEntityManager()->flush();
    }
}
