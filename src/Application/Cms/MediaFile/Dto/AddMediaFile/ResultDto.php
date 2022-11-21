<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\MediaFile\Dto\AddMediaFile;

class ResultDto
{
    public function __construct(
        public string $id,
        public string $url
    ) {
    }
}
