<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Skyeng\MarketingCmsBundle\Domain\Entity\Redirect;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Uri;
use Skyeng\MarketingCmsBundle\Domain\Repository\RedirectRepository\Exception\RedirectNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Repository\RedirectRepository\Exception\RedirectRepositoryException;
use Skyeng\MarketingCmsBundle\Domain\Repository\RedirectRepository\RedirectRepositoryInterface;

class RedirectRepository extends ServiceEntityRepository implements RedirectRepositoryInterface
{
    use LoggerAwareTrait;

    public function __construct(
        ManagerRegistry $registry,
        LoggerInterface $logger
    ) {
        parent::__construct($registry, Redirect::class);
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
            /** @var Redirect[] $redirects */
            $redirects = $this->findBy([]);
        } catch (Exception $e) {
            throw new RedirectRepositoryException($e->getMessage(), $e->getCode(), $e);
        }

        return $redirects;
    }

    /**
     * {@inheritDoc}
     */
    public function getByUri(Uri $uri): Redirect
    {
        try {
            $redirect = $this->createQueryBuilder('red')
                ->innerJoin('red.resource', 'res')
                ->andWhere('res.uri = :uri')
                ->setParameter('uri', $uri->getValue())
                ->getQuery()
                ->getOneOrNullResult();
        } catch (Exception $e) {
            throw new RedirectRepositoryException($e->getMessage(), $e->getCode(), $e);
        }

        if (!$redirect instanceof Redirect) {
            throw new RedirectNotFoundException();
        }

        return $redirect;
    }

    public function save(Redirect $Redirect): void
    {
        $this->getEntityManager()->persist($Redirect);
        $this->getEntityManager()->flush();
    }
}
