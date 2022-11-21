<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\UrlService;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

interface CmsEditorFrontendUrlResolverInterface
{
    public function createUrl(Id $id): string;

    public function showEditorLink(): bool;
}
