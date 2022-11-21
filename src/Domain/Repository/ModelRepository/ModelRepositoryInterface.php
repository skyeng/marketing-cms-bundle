<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Repository\ModelRepository;

use Skyeng\MarketingCmsBundle\Domain\Entity\Model;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Repository\ModelRepository\Exception\ModelRepositoryException;
use Skyeng\MarketingCmsBundle\Domain\Service\Paginator\PaginatorInterface;

interface ModelRepositoryInterface
{
    public function getNextIdentity(): Id;

    public function getById(string $uuid): Model;

    /**
     * @throws ModelRepositoryException
     */
    public function filter(
        string $modelName,
        array $filters = [],
        array $sorts = [],
        int $page = 1,
        int $itemsPerPage = 1,
        ?string $locale = null
    ): PaginatorInterface;

    /**
     * @throws ModelRepositoryException
     */
    public function save(Model $model): void;

    /**
     * @return Id[]
     *
     * @throws ModelRepositoryException
     */
    public function getIdsByTemplate(Id $templateId): array;
}
