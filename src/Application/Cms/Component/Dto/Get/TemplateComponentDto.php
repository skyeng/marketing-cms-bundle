<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Component\Dto\Get;

use OpenApi\Annotations as OA;

class TemplateComponentDto
{
    /**
     * @param mixed[] $data
     */
    public function __construct(
        public string $id,
        public ?string $selector,
        /**
         * @OA\Property(type="array", @OA\Items(type="string"))
         */
        public array $data,
        public bool $isPublished,
        public int $order
    ) {
    }
}
