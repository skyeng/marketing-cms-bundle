<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\DependencyInjection;

use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Locale\LocaleConfiguration;
use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\ModelsConfiguration;
use Skyeng\MarketingCmsBundle\Infrastructure\EasyAdmin\FieldFactory\EasyAdminFieldFactoryInterface;
use Skyeng\MarketingCmsBundle\Infrastructure\EasyAdmin\FieldsFactory\FieldsFactory;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\EventSubscriber\ModelWrapperAliasCreator;
use Skyeng\MarketingCmsBundle\UI\Controller\Admin\ModelCrudController;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;

class MarketingCmsExtension extends Extension implements PrependExtensionInterface, CompilerPassInterface
{
    private const CONFIG_DIR = __DIR__.'/../../../Resources/config/';
    private const CONFIG_PACKAGES_DIR = self::CONFIG_DIR.'packages/';
    /**
     * @var string[]
     */
    private const CONFIGS_FILES = [
        'doctrine.yaml',
        'nelmio_api_doc.yaml',
        'vich_uploader.yaml',
    ];

    /**
     * @param string[] $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(self::CONFIG_DIR));
        $loader->load('services.yaml');

        $definition = $container->getDefinition(LocaleConfiguration::class);
        $definition->setArgument(0, $config['locales']);
        $definition->setArgument(1, $config['default_locale']);

        $definition = $container->getDefinition(ModelsConfiguration::class);
        $definition->setArgument(0, $config['models']);

        $container->setParameter('marketing_cms.editor_definition', $config['editor']);

        $container->registerForAutoconfiguration(EasyAdminFieldFactoryInterface::class)
            ->addTag('marketing_cms.easy_admin_field_factory');

        $definition = $container->getDefinition(ModelWrapperAliasCreator::class);
        $definition->setArgument(0, array_keys($config['models']));

        $this->createModelWrappers($container, $config);
    }

    public function process(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(FieldsFactory::class);

        foreach (array_keys($container->findTaggedServiceIds('marketing_cms.easy_admin_field_factory')) as $id) {
            $definition->addMethodCall('addEasyAdminFieldFactory', [new Reference($id)]);
        }
    }

    public function prepend(ContainerBuilder $container): void
    {
        $this->prependPackagesConfigs($container);
        $this->prependValidator($container);
    }

    private function createModelWrappers(ContainerBuilder $container, array $config): void
    {
        foreach (array_keys($config['models']) as $modelName) {
            $definition = (new Definition())
                ->setAutowired(true)
                ->setAutoconfigured(true)
                ->setClass(ModelCrudController::class)
                ->setArgument('$modelName', $modelName);

            $container->setDefinition(sprintf('%s.CRUD', $modelName), $definition);
        }
    }

    private function prependPackagesConfigs(ContainerBuilder $container): void
    {
        foreach (self::CONFIGS_FILES as $configFile) {
            $config = Yaml::parseFile(self::CONFIG_PACKAGES_DIR.$configFile);

            foreach ($config as $extensionName => $configValues) {
                $container->prependExtensionConfig($extensionName, $configValues);
            }
        }
    }

    private function prependValidator(ContainerBuilder $container): void
    {
        $container->prependExtensionConfig(
            'framework',
            [
                'validation' => [
                    'mapping' => [
                        'paths' => [self::CONFIG_DIR.'/validator/validation.yaml'],
                    ],
                ],
            ]
        );
    }
}
