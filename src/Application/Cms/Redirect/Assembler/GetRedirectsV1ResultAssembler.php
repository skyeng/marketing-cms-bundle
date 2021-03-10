<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Redirect\Assembler;

use Skyeng\MarketingCmsBundle\Application\Cms\Redirect\Dto\GetRedirectsV1ResultDto;
use Skyeng\MarketingCmsBundle\Application\Cms\Redirect\Dto\GetRedirectsV1ResultItemDto;

class GetRedirectsV1ResultAssembler implements GetRedirectsV1ResultAssemblerInterface
{
    /**
     * @inheritDoc
     */
    public function assemble(array $redirects): GetRedirectsV1ResultDto
    {
        $result = new GetRedirectsV1ResultDto();
        $result->result = [];

        foreach ($redirects as $redirect) {
            $result->result[] = new GetRedirectsV1ResultItemDto(
                $redirect->getResource()->getUri()->getValue(),
                $redirect->getTargetUrl(),
                $redirect->getHttpCode()
            );
        }

        return $result;
    }
}
