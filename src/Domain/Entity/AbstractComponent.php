<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Entity;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\ComponentName;

abstract class AbstractComponent
{
    abstract public function getName(): ComponentName;

    /**
     * @return mixed[]
     */
    abstract public function getData(): array;

    abstract public function getOrder(): int;
}
