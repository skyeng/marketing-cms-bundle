<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Page\Assembler;

use Skyeng\MarketingCmsBundle\Application\Cms\Page\Dto\GetPageV1RequestDto;
use Skyeng\MarketingCmsBundle\Application\Cms\Page\Dto\GetPageV1ResultDto;
use Skyeng\MarketingCmsBundle\Domain\Entity\Page;

interface GetPageV1ResultAssemblerInterface
{
    public function assemble(Page $page): GetPageV1ResultDto;
}
