<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Factory\Model;

use Skyeng\MarketingCmsBundle\Domain\Entity\Field;
use Skyeng\MarketingCmsBundle\Domain\Entity\FieldType;
use Skyeng\MarketingCmsBundle\Domain\Entity\Model;
use Skyeng\MarketingCmsBundle\Domain\Factory\Component\ComponentFactoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Factory\Model\Exception\ModelCannotBeClonedException;
use Skyeng\MarketingCmsBundle\Domain\Repository\ModelRepository\ModelRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\FieldConfig;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\ModelConfig;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\ModelsConfigurationInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Hook\Event\PreCloneHookEvent;
use Skyeng\MarketingCmsBundle\Domain\Service\Hook\Event\PreCreateHookEvent;
use Skyeng\MarketingCmsBundle\Domain\Service\Hook\Manager\HookManagerInterface;
use Throwable;

class ModelFactory implements ModelFactoryInterface
{
    public function __construct(
        private ModelRepositoryInterface $modelRepository,
        private ModelsConfigurationInterface $modelsConfiguration,
        private HookManagerInterface $hookManager,
        private ComponentFactoryInterface $componentFactory
    ) {
    }

    public function create(string $name): Model
    {
        return new Model(
            $this->modelRepository->getNextIdentity(),
            $name,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function clone(Model $model): Model
    {
        $modelConfig = $this->modelsConfiguration->getModelConfig($model->getName());

        if (!$modelConfig->isCloneable()) {
            throw new ModelCannotBeClonedException(sprintf('Model «%s» is not cloneable', $model->getName()));
        }

        try {
            $newModel = $this->create($model->getName());

            $this->cloneModelFieldsToNewModel($model, $newModel, $modelConfig);

            $this->hookManager->handle(new PreCloneHookEvent($newModel));
            $this->hookManager->handle(new PreCreateHookEvent($newModel));
        } catch (Throwable $e) {
            throw new ModelCannotBeClonedException($e->getMessage(), $e->getCode(), $e);
        }

        return $newModel;
    }

    /**
     * @throws ModelCannotBeClonedException
     */
    private function cloneModelFieldsToNewModel(Model $model, Model $newModel, ModelConfig $modelConfig): void
    {
        foreach ($model->getFields() as $field) {
            $value = $field->getValue();

            $fieldConfig = $this->getFieldConfig($modelConfig, $field->getName());

            if ($field->getType() === FieldType::COMPONENTS || !$fieldConfig->isCloneable()) {
                $value = null;
            }

            $newField = new Field(
                $newModel,
                $field->getName(),
                $value,
                $field->getType(),
                $field->getLocale(),
            );

            if ($field->getType() === FieldType::COMPONENTS) {
                $this->cloneFieldComponentsToNewField($field, $newField);
            }

            $newModel->addField($newField);
        }
    }

    private function cloneFieldComponentsToNewField(Field $field, Field $newField): void
    {
        $newComponents = [];

        foreach ($field->getComponents() as $component) {
            $newComponents[] = $this->componentFactory->create(
                $field,
                $component->getName(),
                $component->getData(),
                $component->getOrder(),
                $component->isPublished(),
            );
        }

        $newField->setValue($newComponents);
    }

    /**
     * @throws ModelCannotBeClonedException
     */
    private function getFieldConfig(ModelConfig $modelConfig, string $fieldName): FieldConfig
    {
        $fieldConfig = $modelConfig->findFieldConfig($fieldName);

        if (!$fieldConfig instanceof FieldConfig) {
            throw new ModelCannotBeClonedException(sprintf('Поле «%s» не описано в конфигурации', $fieldName));
        }

        return $fieldConfig;
    }
}
