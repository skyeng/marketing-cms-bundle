<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Component\Dto\Get;

class ComponentsDto
{
    /**
     * @var ComponentDto[]
     */
    public array $components = [];

    public function __construct(ComponentDto ...$components)
    {
        $this->components = $components;
    }
}
