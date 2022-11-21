<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Template\Assembler;

use Skyeng\MarketingCmsBundle\Application\Cms\Template\Dto\GetTemplatesV1ResultDto;
use Skyeng\MarketingCmsBundle\Domain\Entity\Template;

interface GetTemplatesV1ResultAssemblerInterface
{
    public function assemble(Template ...$templates): GetTemplatesV1ResultDto;
}
