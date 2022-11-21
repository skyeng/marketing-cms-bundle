<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Component\Dto\Get;

class ComponentDto
{
    /**
     * @param string[] $data
     */
    public function __construct(
        public string $id,
        public string $selector,
        /**
         * @var string[]
         */
        public array $data,
        public bool $isTemplate,
        public bool $isPublished,
        public int $order,
        public ?TemplateDto $template
    ) {
    }
}
