<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Repository;

use Skyeng\MarketingCmsBundle\Domain\Entity\File;
use Skyeng\MarketingCmsBundle\Domain\Repository\FileRepository\Exception\FileNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Repository\FileRepository\Exception\FileRepositoryException;
use Skyeng\MarketingCmsBundle\Domain\Repository\FileRepository\FileRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

class FileRepository extends ServiceEntityRepository implements FileRepositoryInterface
{
    use LoggerAwareTrait;

    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, File::class);
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
            throw new FileRepositoryException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getByUri(string $uri): File
    {
        try {
            /** @var File $file */
            $file = $this->createQueryBuilder('f')
                ->innerJoin('f.resource', 'r')
                ->andWhere('r.uri = :uri')
                ->setParameter('uri', $uri)
                ->getQuery()
                ->getOneOrNullResult();

            if ($file === null) {
                throw new FileNotFoundException();
            }

            return $file;
        } catch (FileNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new FileRepositoryException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function save(File $file): void
    {
        $this->getEntityManager()->persist($file);
        $this->getEntityManager()->flush($file);
    }
}
