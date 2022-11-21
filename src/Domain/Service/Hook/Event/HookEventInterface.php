<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Hook\Event;

use Skyeng\MarketingCmsBundle\Domain\Entity\Model;

interface HookEventInterface
{
    public function getModel(): Model;
}
