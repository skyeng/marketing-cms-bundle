<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\UseCase\Component\Dto;

use Skyeng\MarketingCmsBundle\Domain\Entity\Field;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\ComponentName;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;

class UpdatedComponentDto
{
    public function __construct(
        private ?Id $id,
        private ComponentName $componentName,
        private Field $field,
        private ?string $jsonData,
        private ?string $templateId,
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

    public function getField(): Field
    {
        return $this->field;
    }

    public function getJsonData(): ?string
    {
        return $this->jsonData;
    }

    public function getTemplateId(): ?string
    {
        return $this->templateId;
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
