<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Repository\ResourceRepository;

use Skyeng\MarketingCmsBundle\Domain\Entity\Resource as ResourceEntity;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Repository\ResourceRepository\Exception\ResourceNotFoundException;
use Skyeng\MarketingCmsBundle\Domain\Repository\ResourceRepository\Exception\ResourceRepositoryException;

interface ResourceRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @return ResourceEntity[]
     *
     * @throws ResourceRepositoryException
     */
    public function getAll(): array;

    /**
     * @throws ResourceRepositoryException
     * @throws ResourceNotFoundException
     */
    public function getByUri(string $uri): ResourceEntity;
}
