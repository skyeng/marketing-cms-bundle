<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Repository\ComponentRepository;

use Skyeng\MarketingCmsBundle\Domain\Entity\Component;
use Skyeng\MarketingCmsBundle\Domain\Entity\Model;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Repository\ComponentRepository\Exception\ComponentRepositoryException;

interface ComponentRepositoryInterface
{
    public function getNextIdentity(): Id;

    public function save(Component ...$components): void;

    public function remove(Component ...$components): void;

    /**
     * @return Component[]
     *
     * @throws ComponentRepositoryException
     */
    public function getByModel(Model $model): array;
}
