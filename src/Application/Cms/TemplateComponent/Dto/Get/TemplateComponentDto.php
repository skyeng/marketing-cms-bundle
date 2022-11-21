<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\TemplateComponent\Dto\Get;

use OpenApi\Annotations as OA;

class TemplateComponentDto
{
    /**
     * @param mixed[]|null $data
     */
    public function __construct(
        public string $id,
        public ?string $selector,
        /**
         * @OA\Property(type="array", @OA\Items(type="string"))
         *
         * @var mixed[]|null
         */
        public ?array $data,
        public bool $isPublished,
        public int $order
    ) {
    }
}
