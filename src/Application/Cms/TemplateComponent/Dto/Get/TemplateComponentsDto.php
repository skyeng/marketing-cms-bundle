<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\TemplateComponent\Dto\Get;

class TemplateComponentsDto
{
    /**
     * @var TemplateComponentDto[]
     */
    public array $components = [];

    public function __construct(
        public string $title,
        TemplateComponentDto ...$components
    ) {
        $this->components = $components;
    }
}
