<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Factory\Model;

use Skyeng\MarketingCmsBundle\Domain\Entity\Model;
use Skyeng\MarketingCmsBundle\Domain\Factory\Model\Exception\ModelCannotBeClonedException;

interface ModelFactoryInterface
{
    public function create(string $name): Model;

    /**
     * @throws ModelCannotBeClonedException
     */
    public function clone(Model $model): Model;
}
