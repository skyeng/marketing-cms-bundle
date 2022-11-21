<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Ramsey\Uuid\Uuid;
use Skyeng\MarketingCmsBundle\Domain\Entity\Component;
use Skyeng\MarketingCmsBundle\Domain\Entity\Field;
use Skyeng\MarketingCmsBundle\Domain\Entity\Model;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Repository\ComponentRepository\ComponentRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Repository\ComponentRepository\Exception\ComponentRepositoryException;

class ComponentRepository extends ServiceEntityRepository implements ComponentRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Component::class);
    }

    public function getNextIdentity(): Id
    {
        $uuid = Uuid::uuid4();

        return new Id($uuid->toString());
    }

    public function save(Component ...$components): void
    {
        $em = $this->getEntityManager();

        try {
            $em->beginTransaction();

            foreach ($components as $component) {
                $em->persist($component);
            }
            $em->flush();

            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            throw new ComponentRepositoryException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function remove(Component ...$components): void
    {
        $em = $this->getEntityManager();

        try {
            $em->beginTransaction();

            foreach ($components as $component) {
                $em->remove($component);
            }
            $em->flush();

            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            throw new ComponentRepositoryException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getByModel(Model $model): array
    {
        try {
            $qb = $this->createQueryBuilder('pc');

            return $qb
                ->join(Field::class, 'f', Join::WITH, $qb->expr()->eq('pc.field', 'f.id'))
                ->join(Model::class, 'm', Join::WITH, $qb->expr()->eq('f.model', 'm.id'))
                ->where('m.id = :modelId')
                ->setParameter('modelId', $model->getId()->getValue())
                ->getQuery()
                ->getResult();
        } catch (Exception $e) {
            throw new ComponentRepositoryException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
