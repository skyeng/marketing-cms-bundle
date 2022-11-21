<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Hook;

use Skyeng\MarketingCmsBundle\Domain\Exception\DomainException;
use Skyeng\MarketingCmsBundle\Domain\Service\Hook\Event\HookEventInterface;

interface HookInterface
{
    /**
     * @throws DomainException
     */
    public function handle(HookEventInterface $hookEvent): void;

    public function supports(HookEventInterface $hookEvent): bool;

    public function getName(): string;
}
