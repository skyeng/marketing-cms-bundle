<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Template\Dto;

class TemplateDto
{
    public function __construct(
        public string $id,
        public string $title,
        public bool $isTemplate
    ) {
    }
}
