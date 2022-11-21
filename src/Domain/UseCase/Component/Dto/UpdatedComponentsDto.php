<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\UseCase\Component\Dto;

final class UpdatedComponentsDto
{
    /**
     * @var UpdatedComponentDto[]
     */
    private array $updatedComponents = [];

    public function __construct(UpdatedComponentDto ...$updatedComponents)
    {
        $this->updatedComponents = $updatedComponents;
    }

    /**
     * @return UpdatedComponentDto[]
     */
    public function toUpdatedComponentDtoArray(): array
    {
        return $this->updatedComponents;
    }
}
