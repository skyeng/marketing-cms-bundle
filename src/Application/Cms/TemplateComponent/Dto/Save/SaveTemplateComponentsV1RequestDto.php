<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\TemplateComponent\Dto\Save;

final class SaveTemplateComponentsV1RequestDto
{
    public string $templateId;

    /**
     * @var ComponentDto[]|null
     */
    public ?array $components = null;
}
