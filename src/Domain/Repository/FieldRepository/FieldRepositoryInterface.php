<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Repository\FieldRepository;

use Skyeng\MarketingCmsBundle\Domain\Entity\Field;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Repository\FieldRepository\Exception\FieldRepositoryException;

interface FieldRepositoryInterface
{
    /**
     * @param Id[] $excludedIds
     *
     * @return Field[]
     *
     * @throws FieldRepositoryException
     */
    public function findByName(
        string $name,
        ?string $value = null,
        array $excludedIds = [],
        ?string $modelName = null
    ): array;
}
