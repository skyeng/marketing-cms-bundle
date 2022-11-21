<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model;

use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\FieldConfig;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\ModelConfig;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\Option\ChoiceConfig;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\Option\OptionsConfig;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Exception\NotFoundModelConfigException;

class ModelsConfiguration implements ModelsConfigurationInterface
{
    /**
     * @var array<string, ModelConfig>
     */
    private array $modelConfigs = [];

    /**
     * @param array<string, array> $modelsDefinition
     */
    public function __construct(array $modelsDefinition)
    {
        foreach ($modelsDefinition as $modelArray) {
            $fields = [];

            foreach ($modelArray['fields'] as $fieldArray) {
                $fields[$fieldArray['name']] = new FieldConfig(
                    $fieldArray['name'],
                    $fieldArray['label'],
                    $fieldArray['type'],
                    $fieldArray['localized'],
                    $fieldArray['required'],
                    $fieldArray['hide_on_form'],
                    $fieldArray['hide_on_index'],
                    $fieldArray['cloneable'],
                    $fieldArray['hooks'],
                    $fieldArray['group'],
                    new OptionsConfig(
                        array_map(
                            static fn ($choiceArray): ChoiceConfig => new ChoiceConfig($choiceArray['label'], $choiceArray['value']),
                            $fieldArray['options']['choices'] ?? [],
                        ),
                    ),
                );
            }

            $name = (string) $modelArray['name'];

            $this->modelConfigs[$name] = new ModelConfig(
                $name,
                $modelArray['label'],
                $modelArray['cloneable'],
                $fields,
                $modelArray['patternUrl']
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getModelConfigs(): array
    {
        return $this->modelConfigs;
    }

    public function getModelConfig(string $name): ModelConfig
    {
        if (!$this->hasModelConfig($name)) {
            throw new NotFoundModelConfigException(sprintf('Not found config for model «%s»', $name));
        }

        return $this->modelConfigs[$name];
    }

    public function hasModelConfig(string $name): bool
    {
        return isset($this->modelConfigs[$name]);
    }
}
