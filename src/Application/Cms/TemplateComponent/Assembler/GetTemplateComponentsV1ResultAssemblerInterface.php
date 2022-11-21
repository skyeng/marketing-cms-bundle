<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\TemplateComponent\Assembler;

use Skyeng\MarketingCmsBundle\Application\Cms\TemplateComponent\Dto\Get\GetTemplateComponentsV1ResultDto;
use Skyeng\MarketingCmsBundle\Domain\Entity\Template;

interface GetTemplateComponentsV1ResultAssemblerInterface
{
    public function assemble(Template $template): GetTemplateComponentsV1ResultDto;
}
