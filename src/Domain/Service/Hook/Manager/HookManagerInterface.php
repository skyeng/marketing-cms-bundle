<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Hook\Manager;

use Skyeng\MarketingCmsBundle\Domain\Service\Hook\Event\HookEventInterface;

interface HookManagerInterface
{
    public function handle(HookEventInterface $hookEvent): void;
}
