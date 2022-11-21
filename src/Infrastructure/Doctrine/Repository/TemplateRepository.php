<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Ramsey\Uuid\Uuid;
use Skyeng\MarketingCmsBundle\Domain\Entity\Template;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository\Exception\TemplateNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository\Exception\TemplateRepositoryException;
use Skyeng\MarketingCmsBundle\Domain\Repository\TemplateRepository\TemplateRepositoryInterface;
use Throwable;

class TemplateRepository extends ServiceEntityRepository implements TemplateRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Template::class);
    }

    public function getNextIdentity(): Id
    {
        return new Id(Uuid::uuid4()->toString());
    }

    /**
     * {@inheritDoc}
     */
    public function getAll(): array
    {
        try {
            /** @var Template[] $templates */
            $templates = $this->findBy([], ['createdAt' => 'DESC']);
        } catch (Exception $e) {
            throw new TemplateRepositoryException($e->getMessage(), $e->getCode(), $e);
        }

        return $templates;
    }

    /**
     * {@inheritDoc}
     */
    public function getById(string $id, bool $withComponents = false): Template
    {
        try {
            if ($withComponents) {
                $qb = $this->createQueryBuilder('t')
                    ->leftJoin('t.components', 'tc')
                    ->where('t.id = :id')
                    ->setParameter('id', $id);

                $template = $qb->getQuery()->getOneOrNullResult();
            } else {
                $template = $this->findOneBy(['id' => $id]);
            }
        } catch (Exception $e) {
            throw new TemplateRepositoryException($e->getMessage(), $e->getCode(), $e);
        }

        if (!$template instanceof Template) {
            throw new TemplateNotFoundException();
        }

        return $template;
    }

    /**
     * {@inheritDoc}
     */
    public function getByIds(array $ids): array
    {
        try {
            /** @var Template[] $templates */
            $templates = $this->findBy(['id' => $ids]);
        } catch (Exception $e) {
            throw new TemplateRepositoryException($e->getMessage(), $e->getCode(), $e);
        }

        return $templates;
    }

    public function save(Template $template): void
    {
        $em = $this->getEntityManager();

        try {
            $em->beginTransaction();

            $em->persist($template);
            $em->flush();

            $em->commit();
        } catch (Throwable $e) {
            $em->rollback();
            throw new TemplateRepositoryException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
