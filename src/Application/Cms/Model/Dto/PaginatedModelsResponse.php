<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Model\Dto;

class PaginatedModelsResponse
{
    /**
     * @param mixed[] $modelSlice
     */
    public function __construct(
        /**
         * @var mixed[]
         */
        public array $modelSlice,
        public int $total,
        public bool $hasNextPage,
        public int $nextPage,
        public bool $hasPreviousPage,
        public int $previousPage
    ) {
    }
}
