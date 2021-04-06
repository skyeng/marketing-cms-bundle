<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\DependencyInjection;

use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Service\ComponentType\ComponentTypeCollection;
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

        foreach (array_keys($container->findTaggedServiceIds('component_type')) as $serviceId) {
            $extensions[] = $container->getDefinition($serviceId);
        }

        $definition->setArgument(0, $extensions);
    }
}
