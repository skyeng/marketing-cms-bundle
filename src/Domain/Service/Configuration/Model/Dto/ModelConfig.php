<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto;

class ModelConfig
{
    /**
     * @param FieldConfig[] $fieldConfigs
     */
    public function __construct(
        private string $name,
        private string $label,
        private bool $isCloneable,
        /** @var FieldConfig[] */
        private array $fieldConfigs,
        private string $patternUrl
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function isCloneable(): bool
    {
        return $this->isCloneable;
    }

    /**
     * @return FieldConfig[]
     */
    public function getFieldConfigs(): array
    {
        return $this->fieldConfigs;
    }

    public function findFieldConfig(string $name): ?FieldConfig
    {
        return $this->fieldConfigs[$name] ?? null;
    }

    public function getPatternUrl(): string
    {
        return $this->patternUrl;
    }
}
