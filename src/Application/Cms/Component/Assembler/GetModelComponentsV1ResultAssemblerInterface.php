<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Component\Assembler;

use Skyeng\MarketingCmsBundle\Application\Cms\Component\Dto\Get\GetModelComponentsV1ResultDto;
use Skyeng\MarketingCmsBundle\Domain\Entity\Component;

interface GetModelComponentsV1ResultAssemblerInterface
{
    public function assemble(Component ...$components): GetModelComponentsV1ResultDto;
}
