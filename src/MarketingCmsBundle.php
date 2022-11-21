<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle;

use Skyeng\MarketingCmsBundle\Domain\Service\Hook\HookInterface;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\DependencyInjection\CompilerPass\HookCollectionCompilerPass;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\DependencyInjection\MarketingCmsExtension;
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

        $container->registerForAutoconfiguration(HookInterface::class)->addTag('marketing_cms_hook');
        $container->addCompilerPass(new HookCollectionCompilerPass());
    }
}
