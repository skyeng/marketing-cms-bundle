<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Redirect\Dto;

use OpenApi\Annotations as OA;

class GetRedirectsV1ResultItemDto
{
    public function __construct(
        /**
         * @OA\Property(example="/from-some-url")
         */
        public string $from,
        /**
         * @OA\Property(example="https://to-some-url.example")
         */
        public string $to,
        /**
         * @OA\Property(example=301)
         */
        public int $httpCode
    ) {
    }
}
