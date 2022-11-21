<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Service;

use Doctrine\ORM\EntityManagerInterface;
use Skyeng\MarketingCmsBundle\Domain\Entity\Model;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Service\Hook\Manager\HookManagerInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Model\ModelHookEventResolverInterface;

class ModelHookEventResolver implements ModelHookEventResolverInterface
{
    private const MAX_ITEMS = 100;

    public function __construct(
        private EntityManagerInterface $em,
        private HookManagerInterface $hookManager,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function runEvents(array $modelIds, string $eventClass): void
    {
        $query = $this->em->createQueryBuilder()
            ->from(Model::class, 'model')
            ->select('model')
            ->addSelect('field')
            ->leftJoin('model.fields', 'field')
            ->where('model.id IN (:modelIds)')
            ->setParameter('modelIds', array_map(
                static fn (Id $id): string => $id->getValue(),
                $modelIds,
            ))
            ->getQuery();

        while ([] !== $partOfModelIds = array_slice($modelIds, 0, self::MAX_ITEMS)) {
            $modelIds = array_slice($modelIds, self::MAX_ITEMS);

            /** @var Model[] $models */
            $models = $query
                ->setParameter('modelIds', array_map(
                    static fn (Id $id): string => $id->getValue(),
                    $partOfModelIds,
                ))
                ->getResult();

            foreach ($models as $model) {
                $this->hookManager->handle(new $eventClass($model));
            }

            $this->em->flush();
            $this->em->clear();
        }
    }
}
