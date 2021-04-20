<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\PageComponentType\ComponentType;

interface ComponentPreviewInterface
{
    public function getPreview(): string;
}
