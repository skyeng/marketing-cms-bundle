<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\PageComponentForm\Assembler;

use Skyeng\MarketingCmsBundle\Application\Cms\PageComponentForm\Dto\GetPageComponentFormResultDto;
use Skyeng\MarketingCmsBundle\Domain\Entity\PageComponent;

interface GetPageComponentFormV1ResultAssemblerInterface
{
    public function assemble(PageComponent $component): GetPageComponentFormResultDto;
}
