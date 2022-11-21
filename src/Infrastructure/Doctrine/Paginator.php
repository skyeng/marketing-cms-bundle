<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Doctrine;

use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;
use Doctrine\ORM\Tools\Pagination\CountWalker;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Skyeng\MarketingCmsBundle\Domain\Service\Paginator\PaginatorInterface;

class Paginator implements PaginatorInterface
{
    public const ITEMS_PER_PAGE = 10;
    private int $currentPage = 1;
    private array $results = [];
    private int $numResults = 0;

    public function __construct(
        private DoctrineQueryBuilder $queryBuilder,
        private int $itemsPerPage,
        private bool $fetchJoinCollection = true
    ) {
    }

    public function paginate(int $page = 1): PaginatorInterface
    {
        $this->currentPage = max(1, $page);
        $firstResult = ($this->currentPage - 1) * $this->itemsPerPage;

        $query = $this->queryBuilder
            ->setFirstResult($firstResult)
            ->setMaxResults($this->itemsPerPage)
            ->getQuery();

        if (0 === (is_countable($this->queryBuilder->getDQLPart('join')) ? \count($this->queryBuilder->getDQLPart('join')) : 0)) {
            $query->setHint(CountWalker::HINT_DISTINCT, false);
        }

        $paginator = new DoctrinePaginator($query, $this->fetchJoinCollection);

        $useOutputWalkers = !empty($this->queryBuilder->getDQLPart('having'));
        $paginator->setUseOutputWalkers($useOutputWalkers);

        $this->results = iterator_to_array($paginator->getIterator());
        $this->numResults = $paginator->count();

        return $this;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getLastPage(): int
    {
        return (int) ceil($this->numResults / $this->itemsPerPage);
    }

    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    public function hasPreviousPage(): bool
    {
        return $this->currentPage > 1;
    }

    public function getPreviousPage(): int
    {
        return max(1, $this->currentPage - 1);
    }

    public function hasNextPage(): bool
    {
        return $this->currentPage < $this->getLastPage();
    }

    public function getNextPage(): int
    {
        return min($this->getLastPage(), $this->currentPage + 1);
    }

    public function hasToPaginate(): bool
    {
        return $this->numResults > $this->itemsPerPage;
    }

    public function getNumResults(): int
    {
        return $this->numResults;
    }

    /**
     * @return mixed[]
     */
    public function getResults(): array
    {
        return $this->results;
    }
}
