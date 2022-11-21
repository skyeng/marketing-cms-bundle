<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Component\Dto\Save;

final class SaveModelComponentsV1RequestDto
{
    public string $modelId;

    /**
     * @var array<ComponentDto>|null
     */
    public ?array $components = null;
}
