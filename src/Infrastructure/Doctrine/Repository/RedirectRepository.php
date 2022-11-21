<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Repository;

use Skyeng\MarketingCmsBundle\Domain\Entity\Redirect;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Uri;
use Skyeng\MarketingCmsBundle\Domain\Repository\RedirectRepository\Exception\RedirectNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Repository\RedirectRepository\Exception\RedirectRepositoryException;
use Skyeng\MarketingCmsBundle\Domain\Repository\RedirectRepository\RedirectRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

class RedirectRepository extends ServiceEntityRepository implements RedirectRepositoryInterface
{
    use LoggerAwareTrait;

    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, Redirect::class);
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
            throw new RedirectRepositoryException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getByUri(Uri $uri): Redirect
    {
        try {
            /** @var Redirect $redirect */
            $redirect = $this->createQueryBuilder('red')
                ->innerJoin('red.resource', 'res')
                ->andWhere('res.uri = :uri')
                ->setParameter('uri', $uri->getValue())
                ->getQuery()
                ->getOneOrNullResult();
        } catch (Exception $e) {
            throw new RedirectRepositoryException($e->getMessage(), $e->getCode(), $e);
        }

        if ($redirect === null) {
            throw new RedirectNotFoundException();
        }

        return $redirect;
    }

    public function save(Redirect $Redirect): void
    {
        $this->getEntityManager()->persist($Redirect);
        $this->getEntityManager()->flush($Redirect);
    }
}
