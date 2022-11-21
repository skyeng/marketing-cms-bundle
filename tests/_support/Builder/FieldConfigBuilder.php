<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Builder;

use Skyeng\MarketingCmsBundle\Domain\Entity\FieldType;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\FieldConfig;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\Option\OptionsConfig;

class FieldConfigBuilder
{
    private string $name = 'title';

    private string $label = 'Заголовок';

    private string $type = FieldType::TEXT;

    private bool $localized = true;

    private bool $required = true;

    private bool $hideOnForm = false;

    private bool $hideOnIndex = false;

    private bool $cloneable = true;

    /**
     * @var string[]
     */
    private array $hooks = [];

    private string $group = 'Основное';

    private OptionsConfig $optionsConfig;

    public function __construct()
    {
        $this->optionsConfig = new OptionsConfig([]);
    }

    public static function fieldConfig(): self
    {
        return new self();
    }

    public function build(): FieldConfig
    {
        return new FieldConfig(
            $this->name,
            $this->label,
            $this->type,
            $this->localized,
            $this->required,
            $this->hideOnForm,
            $this->hideOnIndex,
            $this->cloneable,
            $this->hooks,
            $this->group,
            $this->optionsConfig
        );
    }

    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function withLocale(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function withType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function withLocalized(bool $localized): self
    {
        $this->localized = $localized;

        return $this;
    }

    public function withRequired(bool $required): self
    {
        $this->required = $required;

        return $this;
    }

    public function withHideOnForm(bool $hideOnForm): self
    {
        $this->hideOnForm = $hideOnForm;

        return $this;
    }

    public function withHideOnIndex(bool $hideOnIndex): self
    {
        $this->hideOnIndex = $hideOnIndex;

        return $this;
    }

    public function withCloneable(bool $cloneable): self
    {
        $this->cloneable = $cloneable;

        return $this;
    }

    /**
     * @param string[] $hooks
     */
    public function withHooks(array $hooks): self
    {
        $this->hooks = $hooks;

        return $this;
    }

    public function withGroup(string $group): self
    {
        $this->group = $group;

        return $this;
    }

    public function withOptionsConfig(OptionsConfig $optionsConfig): self
    {
        $this->optionsConfig = $optionsConfig;

        return $this;
    }
}
