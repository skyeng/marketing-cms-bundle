<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Paginator;

interface PaginatorInterface
{
    public function paginate(int $page): self;

    public function getCurrentPage(): int;

    public function getLastPage(): int;

    public function getItemsPerPage(): int;

    public function hasPreviousPage(): bool;

    public function getPreviousPage(): int;

    public function hasNextPage(): bool;

    public function getNextPage(): int;

    public function hasToPaginate(): bool;

    public function getNumResults(): int;

    /**
     * @return mixed[]
     */
    public function getResults(): array;
}
