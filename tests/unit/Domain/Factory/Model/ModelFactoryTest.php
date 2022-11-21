<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Unit\Domain\Factory\Model;

use Codeception\Test\Unit;
use Ramsey\Uuid\Uuid;
use Skyeng\MarketingCmsBundle\Domain\Entity\Component;
use Skyeng\MarketingCmsBundle\Domain\Entity\Field;
use Skyeng\MarketingCmsBundle\Domain\Entity\FieldType;
use Skyeng\MarketingCmsBundle\Domain\Entity\ValueObject\Id;
use Skyeng\MarketingCmsBundle\Domain\Factory\Component\ComponentFactoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Factory\Model\Exception\ModelCannotBeClonedException;
use Skyeng\MarketingCmsBundle\Domain\Factory\Model\ModelFactory;
use Skyeng\MarketingCmsBundle\Domain\Repository\ModelRepository\ModelRepositoryInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\FieldConfig;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\ModelConfig;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Dto\Option\OptionsConfig;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\ModelsConfigurationInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Hook\Manager\HookManagerInterface;
use Skyeng\MarketingCmsBundle\Tests\Builder\ComponentBuilder;
use Skyeng\MarketingCmsBundle\Tests\Builder\FieldBuilder;
use Skyeng\MarketingCmsBundle\Tests\Builder\ModelBuilder;

class ModelFactoryTest extends Unit
{
    public function testCorrectCloneModel(): void
    {
        $model = ModelBuilder::model()
            ->withFields([
                FieldBuilder::field()
                    ->withName('title')
                    ->withValue('Model Title')
                    ->build(),
                FieldBuilder::field()
                    ->withName('slug')
                    ->withValue('model_slug')
                    ->build(),
            ])
            ->build();

        $componentsField = FieldBuilder::field()
            ->withName('components')
            ->withModel($model)
            ->withType(FieldType::COMPONENTS)
            ->build();

        $componentsField->setValue([
            ComponentBuilder::component()
                ->withData(['key' => 'value'])
                ->withField($componentsField)
                ->build(),
        ]);

        $model->addField($componentsField);

        /** @var ModelsConfigurationInterface $modelsConfiguration */
        $modelsConfiguration = $this->makeEmpty(ModelsConfigurationInterface::class, [
            'getModelConfig' => new ModelConfig(
                'article',
                'Статья',
                true,
                [
                    'title' => new FieldConfig(
                        'title',
                        'Заголовок',
                        FieldType::TEXT,
                        false,
                        true,
                        false,
                        true,
                        true,
                        [],
                        'group',
                        new OptionsConfig([])
                    ),
                    'slug' => new FieldConfig(
                        'slug',
                        'Ссылка',
                        FieldType::TEXT,
                        false,
                        true,
                        false,
                        true,
                        true,
                        [],
                        'group',
                        new OptionsConfig([])
                    ),
                    'components' => new FieldConfig(
                        'components',
                        'Компоненты',
                        FieldType::COMPONENTS,
                        false,
                        false,
                        false,
                        true,
                        true,
                        [],
                        'components',
                        new OptionsConfig([])
                    ),
                ],
                '',
            ),
        ]);
        /** @var HookManagerInterface $hookManager */
        $hookManager = $this->makeEmpty(HookManagerInterface::class);

        /** @var ComponentFactoryInterface $componentFactory */
        $componentFactory = $this->makeEmpty(ComponentFactoryInterface::class, [
            'create' => $this->makeEmpty(Component::class),
        ]);
        /** @var ModelRepositoryInterface $modelRepository */
        $modelRepository = $this->makeEmpty(ModelRepositoryInterface::class, [
            'getNextIdentity' => new Id(Uuid::uuid4()->toString()),
        ]);

        $modelFactory = new ModelFactory(
            $modelRepository,
            $modelsConfiguration,
            $hookManager,
            $componentFactory,
        );

        $newModel = $modelFactory->clone($model);

        $this->assertSame(
            $model->getFields()
                ->map(static function (Field $field) {
                    if ($field->getType() !== FieldType::COMPONENTS) {
                        return $field->getValue();
                    }

                    return null;
                })
                ->toArray(),
            $newModel->getFields()
                ->map(static function (Field $field) {
                    if ($field->getType() !== FieldType::COMPONENTS) {
                        return $field->getValue();
                    }

                    return null;
                })
                ->toArray(),
        );
    }

    public function testCloneNotCloneableModelThrowException(): void
    {
        $model = ModelBuilder::model()
            ->withFields([
                FieldBuilder::field()
                    ->withName('title')
                    ->withValue('Model Title')
                    ->build(),
            ])
            ->build();

        /** @var ModelsConfigurationInterface $modelsConfiguration */
        $modelsConfiguration = $this->makeEmpty(ModelsConfigurationInterface::class, [
            'getModelConfig' => new ModelConfig(
                'article',
                'Статья',
                false,
                [
                    'title' => new FieldConfig(
                        'title',
                        'Заголовок',
                        FieldType::TEXT,
                        false,
                        true,
                        false,
                        true,
                        true,
                        [],
                        'group',
                        new OptionsConfig([])
                    ),
                ],
                '',
            ),
        ]);
        /** @var HookManagerInterface $hookManager */
        $hookManager = $this->makeEmpty(HookManagerInterface::class);
        /** @var ComponentFactoryInterface $componentFactory */
        $componentFactory = $this->makeEmpty(ComponentFactoryInterface::class, [
            'create' => $this->makeEmpty(Component::class),
        ]);
        /** @var ModelRepositoryInterface $modelRepository */
        $modelRepository = $this->makeEmpty(ModelRepositoryInterface::class, [
            'getNextIdentity' => new Id(Uuid::uuid4()->toString()),
        ]);

        $modelFactory = new ModelFactory(
            $modelRepository,
            $modelsConfiguration,
            $hookManager,
            $componentFactory,
        );

        $this->expectExceptionObject(
            new ModelCannotBeClonedException('Model «model» is not cloneable'),
        );

        $modelFactory->clone($model);
    }
}
