<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\Option;

class ChoiceConfig
{
    public function __construct(
        private string $label,
        private string $value
    ) {
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
