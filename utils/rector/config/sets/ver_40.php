<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\Rector\Namespace_\RenameNamespaceRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RenameNamespaceRector::class, [
        'Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\DataFixtures' => 'Skyeng\MarketingCmsBundle\Tests\DataFixtures',
        'Skyeng\MarketingCmsBundle\Infrastructure\DependencyInjection' => 'Skyeng\MarketingCmsBundle\Infrastructure\Symfony\DependencyInjection',
        'Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Locale' => 'Skyeng\MarketingCmsBundle\Infrastructure\Symfony\RequestStack\Locale',
    ]);

    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'Skyeng\MarketingCmsBundle\Domain\Service\CloneModelService\Exception\CloneModelServiceException' => 'Skyeng\MarketingCmsBundle\Domain\Factory\Model\Exception\ModelCannotBeClonedException',
        'Skyeng\MarketingCmsBundle\Application\Cms\Model\Exception\ModelCannotBeClonedException' => 'Skyeng\MarketingCmsBundle\Domain\Factory\Model\Exception\ModelCannotBeClonedException',
        'Skyeng\MarketingCmsBundle\Application\Cms\Model\Exception\NotCloneableModelException' => 'Skyeng\MarketingCmsBundle\Domain\Factory\Model\Exception\ModelCannotBeClonedException',
        'Skyeng\MarketingCmsBundle\Domain\Service\CloneModelService\CloneModelServiceInterface' => 'Skyeng\MarketingCmsBundle\Domain\Factory\Model\ModelFactoryInterface',
        'Skyeng\MarketingCmsBundle\Infrastructure\Service\CloneModelService' => 'Skyeng\MarketingCmsBundle\Domain\Factory\Model\ModelFactory',

        'Skyeng\MarketingCmsBundle\Domain\Service\CloneTemplateService\Exception\CloneTemplateServiceException' => 'Skyeng\MarketingCmsBundle\Domain\Factory\Template\Exception\TemplateCannotBeClonedException',
        'Skyeng\MarketingCmsBundle\Domain\Service\CloneTemplateService\CloneTemplateServiceInterface' => 'Skyeng\MarketingCmsBundle\Domain\Factory\Template\TemplateFactoryInterface',
        'Skyeng\MarketingCmsBundle\Infrastructure\Service\CloneTemplateService' => 'Skyeng\MarketingCmsBundle\Domain\Factory\Template\TemplateFactory',

        'Skyeng\MarketingCmsBundle\Domain\Factory\ComponentFactory' => 'Skyeng\MarketingCmsBundle\Domain\Factory\Component\ComponentFactory',
        'Skyeng\MarketingCmsBundle\Domain\Factory\ComponentFactoryInterface' => 'Skyeng\MarketingCmsBundle\Domain\Factory\Component\ComponentFactoryInterface',
    ]);
};
