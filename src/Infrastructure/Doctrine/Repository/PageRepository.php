<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Repository;

use Skyeng\MarketingCmsBundle\Domain\Entity\Page;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageRepository\Exception\PageNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageRepository\Exception\PageRepositoryException;
use Skyeng\MarketingCmsBundle\Domain\Repository\PageRepository\PageRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Ramsey\Uuid\Uuid;

class PageRepository extends ServiceEntityRepository implements PageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
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
            throw new PageRepositoryException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getByUri(string $uri, ?bool $published = null): Page
    {
        try {
            /** @var Page $page */
            $qb = $this->createQueryBuilder('p')
                ->innerJoin('p.resource', 'r')
                ->andWhere('r.uri = :uri')
                ->setParameter('uri', $uri);

            if ($published !== null) {
                $qb
                    ->andWhere($qb->expr()->eq('p.isPublished', ':published'))
                    ->setParameter('published', $published);
            }

            $page = $qb->getQuery()->getOneOrNullResult();

            if ($page === null) {
                throw new PageNotFoundException();
            }

            return $page;
        } catch (PageNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new PageRepositoryException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getById(string $id): Page
    {
        try {
            /** @var Page $page */
            $page = $this->findOneBy(['id' => $id]);

            if ($page === null) {
                throw new PageNotFoundException();
            }

            return $page;
        } catch (PageNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new PageRepositoryException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function save(Page $page): void
    {
        $this->getEntityManager()->persist($page);
        $this->getEntityManager()->flush($page);
    }
}
