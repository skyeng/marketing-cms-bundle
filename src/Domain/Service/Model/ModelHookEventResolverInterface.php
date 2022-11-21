<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Model;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Service\Hook\Event\HookEventInterface;

interface ModelHookEventResolverInterface
{
    /**
     * @param Id[]                             $modelIds
     * @param class-string<HookEventInterface> $eventClass
     */
    public function runEvents(array $modelIds, string $eventClass): void;
}
