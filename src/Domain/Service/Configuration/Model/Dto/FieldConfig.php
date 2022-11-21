<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto;

use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\Option\OptionsConfig;

class FieldConfig
{
    /**
     * @param string[] $hooks
     */
    public function __construct(
        private string $name,
        private string $label,
        private string $type,
        private bool $localized,
        private bool $required,
        private bool $hideOnForm,
        private bool $hideOnIndex,
        private bool $cloneable,
        /** @var string[] */
        private array $hooks,
        private string $group,
        private OptionsConfig $optionsConfig
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

    public function getType(): string
    {
        return $this->type;
    }

    public function isLocalized(): bool
    {
        return $this->localized;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function isHideOnForm(): bool
    {
        return $this->hideOnForm;
    }

    public function isHideOnIndex(): bool
    {
        return $this->hideOnIndex;
    }

    public function isCloneable(): bool
    {
        return $this->cloneable;
    }

    /**
     * @return string[]
     */
    public function getHooks(): array
    {
        return $this->hooks;
    }

    public function hasHook(string $hookName): bool
    {
        return in_array($hookName, $this->hooks, true);
    }

    public function getGroup(): string
    {
        return $this->group;
    }

    public function getOptionsConfig(): OptionsConfig
    {
        return $this->optionsConfig;
    }
}
