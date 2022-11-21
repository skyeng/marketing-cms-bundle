<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Hook;

use Skyeng\MarketingCmsBundle\Domain\Entity\FieldType;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\ModelsConfigurationInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Hook\Event\HookEventInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Hook\Event\PreCloneHookEvent;
use Skyeng\MarketingCmsBundle\Domain\Service\Hook\Exception\NotSupportedTypeException;
use Skyeng\MarketingCmsBundle\Domain\Service\PropertyName\PropertyNameResolverInterface;

class SetPrefixOnCloneHook implements HookInterface
{
    public function __construct(
        private ModelsConfigurationInterface $modelConfiguration,
        private PropertyNameResolverInterface $propertyNameResolver
    ) {
    }

    public function getName(): string
    {
        return 'setPrefixOnClone';
    }

    /**
     * {@inheritDoc}
     */
    public function handle(HookEventInterface $hookEvent): void
    {
        $model = $hookEvent->getModel();

        if (!$this->modelConfiguration->hasModelConfig($model->getName())) {
            return;
        }

        $modelConfig = $this->modelConfiguration->getModelConfig($model->getName());

        foreach ($modelConfig->getFieldConfigs() as $fieldConfig) {
            if (!$fieldConfig->hasHook($this->getName())) {
                continue;
            }

            $propertyName = $this->propertyNameResolver->getPropertyNameValue($fieldConfig);

            if (!in_array($fieldConfig->getType(), [FieldType::TEXT, FieldType::TEXTAREA], true)) {
                throw new NotSupportedTypeException(sprintf('Hook supported "Text" only, but got "%s"', $fieldConfig->getType()));
            }

            $model->__set($propertyName, sprintf('clone-%s-%s', time(), $model->__get($propertyName)));
        }
    }

    public function supports(HookEventInterface $hookEvent): bool
    {
        return $hookEvent instanceof PreCloneHookEvent;
    }
}
