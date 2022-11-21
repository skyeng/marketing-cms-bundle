<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Utils\SymfonyApplication;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use EasyCorp\Bundle\EasyAdminBundle\EasyAdminBundle;
use Nelmio\ApiDocBundle\NelmioApiDocBundle;
use Skyeng\MarketingCmsBundle\MarketingCmsBundle;
use Skyeng\MarketingCmsBundle\Utils\SymfonyApplication\Controller\DashboardController;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Vich\UploaderBundle\VichUploaderBundle;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        $bundles = [
            FrameworkBundle::class,
            NelmioApiDocBundle::class,
            MonologBundle::class,
            SecurityBundle::class,
            TwigBundle::class,
            DoctrineBundle::class,
            DoctrineMigrationsBundle::class,
            VichUploaderBundle::class,
            EasyAdminBundle::class,
            MarketingCmsBundle::class,
        ];

        foreach ($bundles as $class) {
            yield new $class();
        }
    }

    protected function configureContainer(ContainerConfigurator $container, LoaderInterface $loader, ContainerBuilder $builder): void
    {
        $configDir = $this->getProjectDir().'/utils/symfony-application/config';

        $loader->load($configDir.'/packages/*.yaml', 'glob');

        $loader->load(function (ContainerBuilder $container): void {
            $container->autowire(DashboardController::class, DashboardController::class)
                ->addTag('controller.service_arguments')
                ->setAutoconfigured(true)
                ->setPublic(true);
        });
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $configDir = $this->getProjectDir().'/utils/symfony-application/config';
        $routes->import($configDir.'/routes.yaml');

        $bundleConfigDir = $this->getProjectDir().'/src/Resources/config';
        $routes->import($bundleConfigDir.'/{routes}/'.$this->environment.'/*.yaml');
        $routes->import($bundleConfigDir.'/{routes}/*.yaml');
        $routes->import($bundleConfigDir.'/routes.yaml');
    }

    public function getCacheDir(): string
    {
        return dirname(__DIR__).'/var/cache/'.$this->environment;
    }

    public function getLogDir(): string
    {
        return dirname(__DIR__).'/var/log/'.$this->environment;
    }
}
