<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\TemplateComponent\Dto\Save;

class ComponentDto
{
    public ?string $id = null;
    public string $selector;
    public ?string $data = null;
    public bool $isTemplate = false;
    public ?string $templateId = null;
    public bool $isPublished = false;
    public int $order;
}
