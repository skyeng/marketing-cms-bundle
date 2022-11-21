<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\EasyAdmin\FieldsFactory;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\ModelsConfigurationInterface;
use Skyeng\MarketingCmsBundle\Infrastructure\EasyAdmin\FieldFactory\EasyAdminFieldFactoryInterface;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\DependencyInjection\Configuration;

class FieldsFactory
{
    /**
     * @var array<string, EasyAdminFieldFactoryInterface>
     */
    private array $easyAdminFieldFactories = [];

    public function __construct(private ModelsConfigurationInterface $modelConfiguration)
    {
    }

    public function addEasyAdminFieldFactory(EasyAdminFieldFactoryInterface $easyAdminFieldFactory): void
    {
        $this->easyAdminFieldFactories[$easyAdminFieldFactory->supportedType()] = $easyAdminFieldFactory;
    }

    /**
     * @return FieldInterface[]
     */
    public function createFields(string $modelName): array
    {
        $groups = $this->createGroupedFields($modelName);

        return $this->addPanels($groups);
    }

    /**
     * @return array<string, FieldInterface[]>
     */
    private function createGroupedFields(string $modelName): array
    {
        $groups = [];

        foreach ($this->modelConfiguration->getModelConfig($modelName)->getFieldConfigs() as $fieldConfig) {
            if (!array_key_exists($fieldConfig->getType(), $this->easyAdminFieldFactories)) {
                continue;
            }

            $easyAdminField = $this->easyAdminFieldFactories[$fieldConfig->getType()]->create($fieldConfig);

            if ($fieldConfig->isRequired()) {
                $easyAdminField->setRequired(true);
            }

            if ($fieldConfig->isHideOnIndex()) {
                $easyAdminField->hideOnIndex();
            }

            if ($fieldConfig->isHideOnForm()) {
                $easyAdminField->hideOnForm();
            }

            $groups[$fieldConfig->getGroup()][] = $easyAdminField;
        }

        return $groups;
    }

    /**
     * @return mixed[]
     */
    private function addPanels(array $groups): array
    {
        $result = $groups[Configuration::ROOT_GROUP] ?? [];
        unset($groups[Configuration::ROOT_GROUP]);

        foreach ($groups as $group => $easyAdminFields) {
            $result[] = FormField::addPanel($group)->collapsible();
            $result = array_merge($result, $easyAdminFields);
        }

        return $result;
    }
}
