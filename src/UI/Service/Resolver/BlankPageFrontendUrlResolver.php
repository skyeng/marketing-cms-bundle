<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Service\Resolver;

use Skyeng\MarketingCmsBundle\Domain\Entity\Page;

final class BlankPageFrontendUrlResolver implements PageFrontendUrlResolverInterface
{
    public function resolve(Page $page): string
    {
        return '';
    }
}
