<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\File\Dto;

class GetFileV1ResultDto
{
    /**
     * @var string
     */
    public $content;

    /**
     * @var string
     */
    public $contentType;

    /**
     * @var string
     */
    public $filename;

    /**
     * @var string
     */
    public $cacheTime;

    public function __construct(string $content, string $contentType, string $filename, string $cacheTime)
    {
        $this->content = $content;
        $this->contentType = $contentType;
        $this->filename = $filename;
        $this->cacheTime = $cacheTime;
    }
}
