<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Redirect\Dto;

use Swagger\Annotations as SWG;

class GetRedirectsV1ResultItemDto
{
    /**
     * @var string
     * @SWG\Property(example="/from-some-url")
     */
    public $from;

    /**
     * @var string
     * @SWG\Property(example="https://to-some-url.example")
     */
    public $to;

    /**
     * @var int
     * @SWG\Property(example=301)
     */
    public $httpCode;

    public function __construct(string $from, string $to, int $httpCode)
    {
        $this->from = $from;
        $this->to = $to;
        $this->httpCode = $httpCode;
    }
}
