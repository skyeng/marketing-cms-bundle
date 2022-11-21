<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Hook\Collection;

use Skyeng\MarketingCmsBundle\Domain\Service\Hook\HookInterface;

class HookCollection implements HookCollectionInterface
{
    /**
     * @param HookInterface[] $hooks
     */
    public function __construct(
        /** @var HookInterface[] */
        private array $hooks = []
    ) {
    }

    /**
     * @return HookInterface[]
     */
    public function toArray(): array
    {
        return $this->hooks;
    }
}
