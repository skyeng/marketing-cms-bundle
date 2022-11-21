<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject;

use Skyeng\MarketingCmsBundle\Domain\Traits\ValueObjectTrait;

class CacheTime
{
    use ValueObjectTrait;
    public const CACHE_TIME_1H = '3600';
    public const CACHE_TIME_30M = '1800';
}
