<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;
use Skyeng\MarketingCmsBundle\Domain\Entity\FieldType;
use Skyeng\MarketingCmsBundle\Domain\Entity\Model;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Repository\ModelRepository\Exception\ModelNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Repository\ModelRepository\Exception\ModelRepositoryException;
use Skyeng\MarketingCmsBundle\Domain\Repository\ModelRepository\ModelRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\FieldConfig;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\ModelsConfigurationInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Paginator\PaginatorInterface;
use Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Paginator;
use Throwable;

class ModelRepository extends ServiceEntityRepository implements ModelRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        private ModelsConfigurationInterface $modelConfiguration
    ) {
        parent::__construct($registry, Model::class);
    }

    public function getNextIdentity(): Id
    {
        return new Id(Uuid::uuid4()->toString());
    }

    /**
     * {@inheritDoc}
     */
    public function filter(
        string $modelName,
        array $filters = [],
        array $sorts = [],
        int $page = 1,
        int $itemsPerPage = Paginator::ITEMS_PER_PAGE,
        ?string $locale = null
    ): PaginatorInterface {
        if (!$this->modelConfiguration->hasModelConfig($modelName)) {
            throw new ModelRepositoryException(sprintf('Config for model "%s" does not exist', $modelName));
        }

        $modelConfig = $this->modelConfiguration->getModelConfig($modelName);

        $qb = $this->createQueryBuilder('model');

        $subQb = $this->createQueryBuilder('m')
            ->select('m.id')
            ->where('m.name = :model_name');

        $qb->setParameter('model_name', $modelName);

        $expr = $this->getEntityManager()->getExpressionBuilder();

        $filterExpr = $expr->orX();

        foreach ($filters as $fieldName => $value) {
            $fieldConfig = $modelConfig->findFieldConfig($fieldName);

            if (!$fieldConfig instanceof FieldConfig) {
                throw new ModelRepositoryException(sprintf('Config for field "%s" does not exist', $fieldName));
            }

            $andExpr = $expr->andX()->add(sprintf('filter.name = :filter_name_%s', $fieldName));
            $qb->setParameter(sprintf('filter_name_%s', $fieldName), $fieldName);

            $value = $this->serialize($modelName, $fieldName, $value);

            if ($value === null) {
                $andExpr->add('filter.value IS NULL');
            } else {
                $andExpr->add(sprintf('filter.value = :filter_value_%s', $fieldName));
                $qb->setParameter(sprintf('filter_value_%s', $fieldName), $value);
            }

            if ($locale !== null && $fieldConfig->isLocalized()) {
                $andExpr->add(sprintf('filter.locale = :filter_locale_%s', $fieldName));

                $qb->setParameter(sprintf('filter_locale_%s', $fieldName), $locale);
            }

            $filterExpr->add($andExpr);
        }

        if ($filterExpr->count() > 0) {
            $subQb
                ->innerJoin('m.fields', 'filter')
                ->andWhere($filterExpr)
                ->groupBy('m.id')
                ->having('COUNT(filter) = :count_filter');

            $qb->setParameter('count_filter', $filterExpr->count());
        }

        $qb->where($expr->in('model.id', $subQb->getDQL()));

        foreach ($sorts as $fieldName => $order) {
            $qb
                ->innerJoin('model.fields', 'sort', Join::WITH, 'sort.name = :sort_field_name')
                ->setParameter('sort_field_name', $fieldName)
                ->orderBy('sort.value', $order);

            break;
        }

        return (new Paginator($qb, $itemsPerPage, false))->paginate($page);
    }

    public function getById(string $uuid): Model
    {
        $model = $this->find($uuid);

        if (!$model instanceof Model) {
            throw new ModelNotFoundException();
        }

        return $model;
    }

    private function serialize(string $modelName, string $fieldName, mixed $value): ?string
    {
        $fieldConfig = $this->modelConfiguration->getModelConfig($modelName)->findFieldConfig($fieldName);

        if (!$fieldConfig instanceof FieldConfig) {
            return '';
        }

        $type = $fieldConfig->getType();

        if ($type === FieldType::BOOLEAN) {
            if ($value === 'false' || $value === '' || $value === false) {
                return '';
            }

            return '1';
        }

        return $value;
    }

    public function save(Model $model): void
    {
        $em = $this->getEntityManager();

        try {
            $em->beginTransaction();

            $em->persist($model);
            $em->flush();

            $em->commit();
        } catch (Throwable $e) {
            $em->rollback();
            throw new ModelRepositoryException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getIdsByTemplate(Id $templateId): array
    {
        try {
            /** @var string[] $modelIds */
            $modelIds = $this->createQueryBuilder('model')
                ->select('model.id')
                ->leftJoin('model.fields', 'field')
                ->leftJoin('field.components', 'component')
                ->where("JSON_GET_TEXT(component.data, 'template') = :templateId")
                ->setParameter('templateId', $templateId)
                ->getQuery()
                ->getSingleColumnResult()
            ;

            $result = [];

            foreach ($modelIds as $modelId) {
                $result[] = new Id($modelId);
            }

            return $result;
        } catch (Throwable $e) {
            throw new ModelRepositoryException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
