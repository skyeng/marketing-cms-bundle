<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\UrlService;

use Skyeng\MarketingCmsBundle\Domain\Entity\FieldType;
use Skyeng\MarketingCmsBundle\Domain\Entity\Model;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\ModelConfig;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\ModelsConfigurationInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\PropertyName\PropertyNameResolverInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\UrlService\Exception\NotPossibleToCreateUrlException;
use Skyeng\MarketingCmsBundle\Domain\Service\UrlService\Exception\UnexpectedVariableInPatternUrlException;

final class ModelFrontendUrlResolverService implements ModelFrontendUrlResolverInterface
{
    public function __construct(
        private ModelsConfigurationInterface $modelsConfiguration,
        private PropertyNameResolverInterface $propertyNameResolver
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function createUrl(Model $model): string
    {
        $modelConfig = $this->modelsConfiguration->getModelConfig($model->getName());

        if (!$this->canCreateUrl($modelConfig)) {
            throw new NotPossibleToCreateUrlException();
        }
        $url = $this->substituteVariables($model);
        $this->checkAllVariablesResolved($url);

        return $url;
    }

    public function canCreateUrl(ModelConfig $modelConfig): bool
    {
        return !empty($modelConfig->getPatternUrl());
    }

    private function substituteVariables(Model $model): string
    {
        $modelConfig = $this->modelsConfiguration->getModelConfig($model->getName());
        $result = $modelConfig->getPatternUrl();

        foreach ($modelConfig->getFieldConfigs() as $fieldConfig) {
            if ($fieldConfig->getType() !== FieldType::TEXT) {
                continue;
            }
            $propertyName = $this->propertyNameResolver->getPropertyNameValue($fieldConfig);
            $needle = sprintf('{{%s}}', $fieldConfig->getName());
            $result = str_replace(
                $needle,
                $model->__get($propertyName) ?? '',
                $result
            );
        }

        return $result;
    }

    /**
     * @throws UnexpectedVariableInPatternUrlException
     */
    private function checkAllVariablesResolved(string $url): void
    {
        $matches = [];

        if (1 === preg_match('#{{([^}]+)}}#', $url, $matches)) {
            throw new UnexpectedVariableInPatternUrlException($matches[1]);
        }
    }
}
