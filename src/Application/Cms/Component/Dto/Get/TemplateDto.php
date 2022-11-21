<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Component\Dto\Get;

class TemplateDto
{
    /**
     * @var TemplateComponentDto[]
     */
    public array $components = [];

    public function __construct(
        public string $id,
        public string $title,
        TemplateComponentDto ...$components
    ) {
        $this->components = $components;
    }
}
