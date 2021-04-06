<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle;

use Skyeng\MarketingCmsBundle\Infrastructure\DependencyInjection\MarketingCmsExtension;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\DependencyInjection\ComponentTypeCollectionCompilerPass;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Form\ComponentTypes\ComponentTypeInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MarketingCmsBundle extends Bundle
{
    protected function getContainerExtensionClass(): string
    {
        return MarketingCmsExtension::class;
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->registerForAutoconfiguration(ComponentTypeInterface::class)->addTag('component_type');
        $container->addCompilerPass(new ComponentTypeCollectionCompilerPass());
    }
}
