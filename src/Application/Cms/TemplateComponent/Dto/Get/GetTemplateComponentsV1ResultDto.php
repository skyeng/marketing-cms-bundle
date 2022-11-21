<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\TemplateComponent\Dto\Get;

class GetTemplateComponentsV1ResultDto
{
    public function __construct(public TemplateComponentsDto $result)
    {
    }
}
