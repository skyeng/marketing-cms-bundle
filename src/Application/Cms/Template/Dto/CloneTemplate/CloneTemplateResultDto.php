<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Template\Dto\CloneTemplate;

class CloneTemplateResultDto
{
    public function __construct(
        public string $url,
    ) {
    }
}
