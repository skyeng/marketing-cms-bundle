<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\Component\Dto\Get;

final class GetModelComponentsV1ResultDto
{
    public function __construct(
        /**
         * Компоненты.
         */
        public ComponentsDto $result
    ) {
    }
}
