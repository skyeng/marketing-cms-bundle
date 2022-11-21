<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\File\Dto;

class GetFileV1ResultDto
{
    public function __construct(
        public string $content,
        public string $contentType,
        public string $filename,
        public string $cacheTime
    ) {
    }
}
