<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\Option;

class OptionsConfig
{
    /**
     * @param ChoiceConfig[] $choices
     */
    public function __construct(
        /** @var ChoiceConfig[] */
        private array $choices
    ) {
    }

    /**
     * @return ChoiceConfig[]
     */
    public function getChoices(): array
    {
        return $this->choices;
    }
}
