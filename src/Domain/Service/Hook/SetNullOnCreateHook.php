<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Hook;

use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\ModelsConfigurationInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Hook\Event\HookEventInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Hook\Event\PreCreateHookEvent;
use Skyeng\MarketingCmsBundle\Domain\Service\PropertyName\PropertyNameResolverInterface;

final class SetNullOnCreateHook implements HookInterface
{
    public function __construct(
        private ModelsConfigurationInterface $modelConfiguration,
        private PropertyNameResolverInterface $propertyNameResolver
    ) {
    }

    public function getName(): string
    {
        return 'setNullOnCreate';
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

            if ($model->__get($propertyName) !== null) {
                continue;
            }

            $model->__set($propertyName, null);
        }
    }

    public function supports(HookEventInterface $hookEvent): bool
    {
        return $hookEvent instanceof PreCreateHookEvent;
    }
}
