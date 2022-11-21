<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\UseCase\TemplateComponent\Dto;

use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\ComponentName;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

class UpdatedTemplateComponentDto
{
    public function __construct(
        private ?Id $id,
        private ComponentName $componentName,
        private ?string $jsonData,
        private bool $isPublished,
        private int $order
    ) {
    }

    public function getId(): ?Id
    {
        return $this->id;
    }

    public function getComponentName(): ComponentName
    {
        return $this->componentName;
    }

    public function getJsonData(): ?string
    {
        return $this->jsonData;
    }

    public function isPublished(): bool
    {
        return $this->isPublished;
    }

    public function getOrder(): int
    {
        return $this->order;
    }
}
