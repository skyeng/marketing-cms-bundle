<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'Skyeng\MarketingCmsBundle\UI\Service\Validator\ValidatorInterface' => 'Skyeng\MarketingCmsBundle\Application\Service\Validator\ValidatorInterface',
        'Skyeng\MarketingCmsBundle\UI\Service\Validator\Validator' => 'Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Validator\Validator',
        'Skyeng\MarketingCmsBundle\UI\Service\Validator\RequestFormValidationHelper' => 'Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Validator\RequestFormValidationHelper',
        'Skyeng\MarketingCmsBundle\UI\Service\Validator\Constraints\UniqueField' => 'Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Validator\Constraints\UniqueField',
        'Skyeng\MarketingCmsBundle\UI\Service\Validator\Constraints\UniqueFieldValidator' => 'Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Validator\Constraints\UniqueFieldValidator',

        'Skyeng\MarketingCmsBundle\UI\Service\Response\HttpApiExceptionListener' => 'Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Response\HttpApiExceptionListener',
        'Skyeng\MarketingCmsBundle\UI\Service\Response\ResponseFactory' => 'Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Response\ResponseFactory',

        'Skyeng\MarketingCmsBundle\Infrastructure\Service\AuthorizationService' => 'Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Security\Authorization\AuthorizationService',
        'Skyeng\MarketingCmsBundle\Infrastructure\Service\CurrentUrlProviderService' => 'Skyeng\MarketingCmsBundle\Infrastructure\Symfony\RequestStack\CurrentUrlProviderService',
    ]);
};
