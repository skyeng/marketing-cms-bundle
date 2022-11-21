<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Unit\Domain\Service;

use Codeception\Test\Unit;
use Skyeng\MarketingCmsBundle\Domain\Entity\Field;
use Skyeng\MarketingCmsBundle\Domain\Entity\FieldType;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Locale\LocaleConfigurationInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\ModelsConfiguration;
use Skyeng\MarketingCmsBundle\Domain\Service\PropertyName\PropertyNameResolver;
use Skyeng\MarketingCmsBundle\Domain\Service\UrlService\Exception\NotPossibleToCreateUrlException;
use Skyeng\MarketingCmsBundle\Domain\Service\UrlService\Exception\UnexpectedVariableInPatternUrlException;
use Skyeng\MarketingCmsBundle\Domain\Service\UrlService\ModelFrontendUrlResolverService;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\RequestStack\Locale\LocaleResolver;
use Skyeng\MarketingCmsBundle\Tests\Builder\ModelBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class ModelFrontendUrlResolverServiceTest extends Unit
{
    public function testCorrectUrl(): void
    {
        $modelsDefinition = [
            'cms_page' => [
                'name' => 'cms_page',
                'label' => 'Страница',
                'cloneable' => true,
                'patternUrl' => 'https://skyeng.ru/{{uri}}-{{description}}',
                'fields' => [
                    [
                        'name' => 'title',
                        'type' => FieldType::TEXT,
                        'label' => 'Название страницы',
                        'localized' => false,
                        'required' => true,
                        'hide_on_form' => false,
                        'hide_on_index' => false,
                        'cloneable' => false,
                        'hooks' => [],
                        'group' => '',
                    ],
                    [
                        'name' => 'description',
                        'type' => FieldType::TEXT,
                        'label' => 'Описание',
                        'localized' => false,
                        'required' => true,
                        'hide_on_form' => false,
                        'hide_on_index' => false,
                        'cloneable' => false,
                        'hooks' => [],
                        'group' => '',
                    ],
                    [
                        'name' => 'uri',
                        'type' => FieldType::TEXT,
                        'label' => 'Адрес ссылки',
                        'localized' => false,
                        'required' => true,
                        'hide_on_form' => false,
                        'hide_on_index' => false,
                        'cloneable' => false,
                        'hooks' => [],
                        'group' => '',
                    ],
                ],
            ],
        ];

        /** @var RequestStack $requestStack */
        $requestStack = $this->makeEmpty(RequestStack::class, [
            'getCurrentRequest' => $this->makeEmpty(Request::class, [
                'get' => 'ru',
            ]),
        ]);

        /** @var LocaleConfigurationInterface $localeConfiguration */
        $localeConfiguration = $this->makeEmpty(LocaleConfigurationInterface::class, [
            'getDefaultLocale' => 'ru',
        ]);

        $localeResolver = new LocaleResolver($requestStack, $localeConfiguration);

        $modelFrontendUrlResolver = new ModelFrontendUrlResolverService(
            new ModelsConfiguration($modelsDefinition),
            new PropertyNameResolver($localeResolver)
        );

        $model = ModelBuilder::model()
            ->withName('cms_page')
            ->build();
        $model->addField(new Field(
            $model,
            'title',
            'Test title',
            FieldType::TEXT
        ));
        $model->addField(new Field(
            $model,
            'description',
            'TestDescription',
            FieldType::TEXT
        ));
        $model->addField(new Field(
            $model,
            'uri',
            'cool-cms-bundle',
            FieldType::TEXT
        ));

        $this->assertSame(
            'https://skyeng.ru/cool-cms-bundle-TestDescription',
            $modelFrontendUrlResolver->createUrl($model),
        );
    }

    public function testTryToCreateUrlWhenItsNotPossible(): void
    {
        $modelsDefinition = [
            'cms_page' => [
                'name' => 'cms_page',
                'label' => 'Страница',
                'cloneable' => true,
                'patternUrl' => '',
                'fields' => [
                    [
                        'name' => 'title',
                        'type' => FieldType::TEXT,
                        'label' => 'Название страницы',
                        'localized' => false,
                        'required' => true,
                        'hide_on_form' => false,
                        'hide_on_index' => false,
                        'cloneable' => false,
                        'hooks' => [],
                        'group' => '',
                    ],
                    [
                        'name' => 'description',
                        'type' => FieldType::TEXT,
                        'label' => 'Описание',
                        'localized' => false,
                        'required' => true,
                        'hide_on_form' => false,
                        'hide_on_index' => false,
                        'cloneable' => false,
                        'hooks' => [],
                        'group' => '',
                    ],
                    [
                        'name' => 'uri',
                        'type' => FieldType::TEXT,
                        'label' => 'Адрес ссылки',
                        'localized' => false,
                        'required' => true,
                        'hide_on_form' => false,
                        'hide_on_index' => false,
                        'cloneable' => false,
                        'hooks' => [],
                        'group' => '',
                    ],
                ],
            ],
        ];

        /** @var RequestStack $requestStack */
        $requestStack = $this->makeEmpty(RequestStack::class, [
            'getCurrentRequest' => $this->makeEmpty(Request::class, [
                'get' => 'ru',
            ]),
        ]);

        /** @var LocaleConfigurationInterface $localeConfiguration */
        $localeConfiguration = $this->makeEmpty(LocaleConfigurationInterface::class, [
            'getDefaultLocale' => 'ru',
        ]);

        $localeResolver = new LocaleResolver($requestStack, $localeConfiguration);

        $modelFrontendUrlResolver = new ModelFrontendUrlResolverService(
            new ModelsConfiguration($modelsDefinition),
            new PropertyNameResolver($localeResolver)
        );

        $model = ModelBuilder::model()
            ->withName('cms_page')
            ->build();
        $model->addField(new Field(
            $model,
            'title',
            'Test title',
            FieldType::TEXT
        ));
        $model->addField(new Field(
            $model,
            'description',
            'TestDescription',
            FieldType::TEXT
        ));
        $model->addField(new Field(
            $model,
            'uri',
            'cool-cms-bundle',
            FieldType::TEXT
        ));

        $this->expectException(NotPossibleToCreateUrlException::class);
        $this->expectExceptionMessage('Trying to create url, but it is not possible');

        $modelFrontendUrlResolver->createUrl($model);
    }

    public function testNotProcessedVariableInPatternUrl(): void
    {
        $modelsDefinition = [
            'cms_page' => [
                'name' => 'cms_page',
                'label' => 'Страница',
                'cloneable' => true,
                'patternUrl' => 'https://skyeng.ru/{{uri}}-{{description}}-{{notExisingVariable}}',
                'fields' => [
                    [
                        'name' => 'title',
                        'type' => FieldType::TEXT,
                        'label' => 'Название страницы',
                        'localized' => false,
                        'required' => true,
                        'hide_on_form' => false,
                        'hide_on_index' => false,
                        'cloneable' => false,
                        'hooks' => [],
                        'group' => '',
                    ],
                    [
                        'name' => 'description',
                        'type' => FieldType::TEXT,
                        'label' => 'Описание',
                        'localized' => false,
                        'required' => true,
                        'hide_on_form' => false,
                        'hide_on_index' => false,
                        'cloneable' => false,
                        'hooks' => [],
                        'group' => '',
                    ],
                    [
                        'name' => 'uri',
                        'type' => FieldType::TEXT,
                        'label' => 'Адрес ссылки',
                        'localized' => false,
                        'required' => true,
                        'hide_on_form' => false,
                        'hide_on_index' => false,
                        'cloneable' => false,
                        'hooks' => [],
                        'group' => '',
                    ],
                ],
            ],
        ];

        /** @var RequestStack $requestStack */
        $requestStack = $this->makeEmpty(RequestStack::class, [
            'getCurrentRequest' => $this->makeEmpty(Request::class, [
                'get' => 'ru',
            ]),
        ]);

        /** @var LocaleConfigurationInterface $localeConfiguration */
        $localeConfiguration = $this->makeEmpty(LocaleConfigurationInterface::class, [
            'getDefaultLocale' => 'ru',
        ]);

        $localeResolver = new LocaleResolver($requestStack, $localeConfiguration);

        $modelFrontendUrlResolver = new ModelFrontendUrlResolverService(
            new ModelsConfiguration($modelsDefinition),
            new PropertyNameResolver($localeResolver)
        );

        $model = ModelBuilder::model()
            ->withName('cms_page')
            ->build();
        $model->addField(new Field(
            $model,
            'title',
            'Test title',
            FieldType::TEXT
        ));
        $model->addField(new Field(
            $model,
            'description',
            'TestDescription',
            FieldType::TEXT
        ));
        $model->addField(new Field(
            $model,
            'uri',
            'cool-cms-bundle',
            FieldType::TEXT
        ));

        $this->expectException(UnexpectedVariableInPatternUrlException::class);
        $this->expectExceptionMessage('Unexpected variable in patternUrl parameter «notExisingVariable»');

        $modelFrontendUrlResolver->createUrl($model);
    }
}
