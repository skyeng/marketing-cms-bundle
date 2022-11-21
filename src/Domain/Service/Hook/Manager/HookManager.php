<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Hook\Manager;

use Skyeng\MarketingCmsBundle\Domain\Entity\Model;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\FieldConfig;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\ModelsConfigurationInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Hook\Collection\HookCollectionInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Hook\Event\HookEventInterface;

class HookManager implements HookManagerInterface
{
    public function __construct(
        private HookCollectionInterface $hookCollection,
        private ModelsConfigurationInterface $modelConfiguration
    ) {
    }

    public function handle(HookEventInterface $hookEvent): void
    {
        foreach ($this->hookCollection->toArray() as $hook) {
            if (!$hook->supports($hookEvent)) {
                continue;
            }

            if ($this->hasHook($hookEvent->getModel(), $hook->getName())) {
                $hook->handle($hookEvent);
            }
        }
    }

    private function hasHook(Model $model, string $hookName): bool
    {
        $fields = array_filter(
            $this->modelConfiguration->getModelConfig($model->getName())->getFieldConfigs(),
            static fn (FieldConfig $fieldConfig): bool => $fieldConfig->hasHook($hookName),
        );

        return $fields !== [];
    }
}
