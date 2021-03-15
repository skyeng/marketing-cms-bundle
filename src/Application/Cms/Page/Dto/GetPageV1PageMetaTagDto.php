<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Page\Dto;

use Swagger\Annotations as SWG;

class GetPageV1PageMetaTagDto
{
    /**
     * Meta tag name
     * @var string|null
     * @SWG\Property(example="robots")
     */
    public $name;

    /**
     * Meta tag property
     * @var string|null
     * @SWG\Property(example="og:url")
     */
    public $property;

    /**
     * Meta tag content
     * @var string
     * @SWG\Property(example="content")
     */
    public $content;

    public function __construct(?string $name, ?string $property, string $content)
    {
        $this->name = $name;
        $this->property = $property;
        $this->content = $content;
    }
}
