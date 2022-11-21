<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Hook\Collection;

use Skyeng\MarketingCmsBundle\Domain\Service\Hook\HookInterface;

interface HookCollectionInterface
{
    /**
     * @return HookInterface[]
     */
    public function toArray(): array;
}
