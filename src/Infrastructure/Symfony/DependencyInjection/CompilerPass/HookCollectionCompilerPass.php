<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\DependencyInjection\CompilerPass;

use Skyeng\MarketingCmsBundle\Domain\Service\Hook\Collection\HookCollection;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class HookCollectionCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(HookCollection::class)) {
            return;
        }

        $definition = $container->getDefinition(HookCollection::class);

        $extensions = [];

        foreach (array_keys($container->findTaggedServiceIds('marketing_cms_hook')) as $serviceId) {
            $extensions[] = $container->getDefinition($serviceId);
        }

        $definition->setArgument(0, $extensions);
    }
}
