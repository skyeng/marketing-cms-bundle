<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Redirect\Assembler;

use Skyeng\MarketingCmsBundle\Application\Cms\Redirect\Dto\GetRedirectsV1ResultDto;
use Skyeng\MarketingCmsBundle\Domain\Entity\Redirect;

interface GetRedirectsV1ResultAssemblerInterface
{
    /**
     * @param Redirect[] $redirects
     */
    public function assemble(array $redirects): GetRedirectsV1ResultDto;
}
