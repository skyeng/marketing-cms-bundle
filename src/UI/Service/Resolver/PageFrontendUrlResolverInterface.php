<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\UI\Service\Resolver;

use Skyeng\MarketingCmsBundle\Domain\Entity\Page;

interface PageFrontendUrlResolverInterface
{
    public function resolve(Page $page): string;
}
