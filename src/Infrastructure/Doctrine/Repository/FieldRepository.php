<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Skyeng\MarketingCmsBundle\Domain\Entity\Field;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Repository\FieldRepository\Exception\FieldRepositoryException;
use Skyeng\MarketingCmsBundle\Domain\Repository\FieldRepository\FieldRepositoryInterface;

class FieldRepository extends ServiceEntityRepository implements FieldRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Field::class);
    }

    /**
     * {@inheritDoc}
     */
    public function findByName(
        string $name,
        ?string $value = null,
        array $excludedIds = [],
        ?string $modelName = null
    ): array {
        try {
            $qb = $this->createQueryBuilder('f');

            $qb->where('f.name = :name')
                ->setParameter('name', $name);

            if ($value === null) {
                $qb->andWhere('f.value IS NULL');
            } else {
                $qb->andWhere('f.value = :value')
                    ->setParameter('value', $value);
            }

            if ($excludedIds !== []) {
                $ids = array_map(
                    static fn (Id $id): string => $id->getValue(),
                    $excludedIds,
                );

                $qb->andWhere($qb->expr()->notIn('f.id', $ids));
            }

            if ($modelName) {
                $qb->leftJoin('f.model', 'm')
                    ->andWhere('m.name = :modelName')
                    ->setParameter('modelName', $modelName);
            }

            return $qb->getQuery()->getResult();
        } catch (Exception $e) {
            throw new FieldRepositoryException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
