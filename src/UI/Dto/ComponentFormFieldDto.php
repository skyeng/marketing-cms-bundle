<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Dto;

class ComponentFormFieldDto
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $type;

    /**
     * @var bool
     */
    public $nullable;

    public function __construct(string $name, string $type, bool $nullable)
    {
        $this->name = $name;
        $this->type = $type;
        $this->nullable = $nullable;
    }
}
