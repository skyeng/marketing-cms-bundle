<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Tests\Helper;

use Codeception\Exception\ModuleException;
use Codeception\Module\Symfony;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

trait ContainerTrait
{
    /**
     * @throws ModuleException
     */
    public function getServiceContainer(): ContainerInterface
    {
        return $this->getModule('Symfony')->grabService('test.service_container');
    }

    /**
     * @throws ModuleException
     */
    public function getAppContainer(): ContainerInterface
    {
        /** @var Symfony $module */
        $module = $this->getModule('Symfony');

        return $module->kernel->getContainer();
    }

    public function getService(string $service)
    {
        $container = $this->getServiceContainer();

        try {
            return $container->get($service);
        } catch (ServiceNotFoundException) {
            $container = $this->getAppContainer();

            return $container->get($service);
        }
    }
}
