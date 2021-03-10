<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;

class MarketingCmsExtension extends Extension implements PrependExtensionInterface
{
    private const CONFIG_DIR = __DIR__ . '/../../Resources/config/';
    private const CONFIG_PACKAGES_DIR = self::CONFIG_DIR . 'packages/';

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(self::CONFIG_DIR));
        $loader->load('services.yaml');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $this->prependPackagesConfigs($container);
        $this->prependValidator($container);
    }

    private function prependPackagesConfigs(ContainerBuilder $container): void
    {
        $configsFiles = [
            'doctrine.yaml',
            'nelmio_api_doc.yaml',
        ];

        foreach ($configsFiles as $configFile) {
            $config = Yaml::parseFile(self::CONFIG_PACKAGES_DIR . $configFile);

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
                        'paths' => [self::CONFIG_DIR . "/validator/validation.yaml"],
                    ],
                ],
            ]
        );
    }
}
