<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\DependencyInjection;

use Skyeng\MarketingCmsBundle\Application\Cms\PageComponentType\Service\ComponentTypeCollection\ComponentTypeCollection;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ComponentTypeCollectionCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(ComponentTypeCollection::class)) {
            return;
        }

        $definition = $container->getDefinition(ComponentTypeCollection::class);

        $extensions = [];

        foreach (array_keys($container->findTaggedServiceIds('cms_component_type')) as $serviceId) {
            $extensions[] = $container->getDefinition($serviceId);
        }

        $definition->setArgument(0, $extensions);
    }
}
