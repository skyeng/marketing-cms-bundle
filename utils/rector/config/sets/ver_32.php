<?php

declare(strict_types=1);

use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Removing\Rector\Class_\RemoveInterfacesRector;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\Rector\Namespace_\RenameNamespaceRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Skyeng\MarketingCmsBundle\Domain\Entity\FieldType;
use Skyeng\MarketingCmsBundle\Domain\Repository\FieldRepository\FieldRepositoryInterface;
use Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Repository\FieldRepository;
use Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Response\ResponseFactory;
use Skyeng\MarketingCmsBundle\Utils\Rector\Rule\RemoveMethodCallParamByNameRector;
use Skyeng\MarketingCmsBundle\Utils\Rector\Rule\ReplaceStringWithConstRector;
use Skyeng\MarketingCmsBundle\Utils\Rector\ValueObject\RemoveMethodCallParamByName;
use Symfony\Component\HttpFoundation\Request;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RemoveInterfacesRector::class, [
        'Skyeng\MarketingCmsBundle\UI\Service\Response\ResponseTransformerServiceInterface',
    ]);

    $rectorConfig->ruleWithConfiguration(RenameNamespaceRector::class, [
        'Skyeng\MarketingCmsBundle\UI\Service\EasyAdminFieldFactories' => 'Skyeng\MarketingCmsBundle\Infrastructure\EasyAdmin\FieldFactory',
    ]);

    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'Skyeng\MarketingCmsBundle\UI\Service\EasyAdminFieldFactories\EasyAdminBooleanFieldFactory' => 'Skyeng\MarketingCmsBundle\Infrastructure\EasyAdmin\FieldFactory\BooleanFieldFactory',
        'Skyeng\MarketingCmsBundle\UI\Service\EasyAdminFieldFactories\EasyAdminChoiceFieldFactory' => 'Skyeng\MarketingCmsBundle\Infrastructure\EasyAdmin\FieldFactory\ChoiceFieldFactory',
        'Skyeng\MarketingCmsBundle\UI\Service\EasyAdminFieldFactories\EasyAdminIntegerFieldFactory' => 'Skyeng\MarketingCmsBundle\Infrastructure\EasyAdmin\FieldFactory\IntegerFieldFactory',
        'Skyeng\MarketingCmsBundle\UI\Service\EasyAdminFieldFactories\EasyAdminTextareaFieldFactory' => 'Skyeng\MarketingCmsBundle\Infrastructure\EasyAdmin\FieldFactory\TextareaFieldFactory',
        'Skyeng\MarketingCmsBundle\UI\Service\EasyAdminFieldFactories\EasyAdminTextFieldFactory' => 'Skyeng\MarketingCmsBundle\Infrastructure\EasyAdmin\FieldFactory\TextFieldFactory',
        'Skyeng\MarketingCmsBundle\UI\Service\EasyAdminFieldFactoryInterface' => 'Skyeng\MarketingCmsBundle\Infrastructure\EasyAdmin\FieldFactory\EasyAdminFieldFactoryInterface',
        'Skyeng\MarketingCmsBundle\UI\Service\EasyAdminFieldsFactory' => 'Skyeng\MarketingCmsBundle\Infrastructure\EasyAdmin\FieldFactory\FieldsFactory',
    ]);

    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
        new MethodCallRename(FieldRepository::class, 'findByValue', 'findByName'),
        new MethodCallRename(FieldRepositoryInterface::class, 'findByValue', 'findByName'),
    ]);

    $rectorConfig->ruleWithConfiguration(RemoveMethodCallParamByNameRector::class, [
        new RemoveMethodCallParamByName(ResponseFactory::class, 'createOkResponse', new ObjectType(Request::class), 'request'),
        new RemoveMethodCallParamByName(ResponseFactory::class, 'createPaginatedOkResponse', new ObjectType(Request::class), 'request'),
        new RemoveMethodCallParamByName(ResponseFactory::class, 'createErrorResponse', new ObjectType(Request::class), 'request'),
        new RemoveMethodCallParamByName(ResponseFactory::class, 'createExceptionResponse', new ObjectType(Request::class), 'request'),
        new RemoveMethodCallParamByName(ResponseFactory::class, 'createFileResponse', new ObjectType(Request::class), 'request'),
    ]);

    $rectorConfig->ruleWithConfiguration(ReplaceStringWithConstRector::class, [
        ReplaceStringWithConstRector::REPLACEMENT_LIST => [
            ['DateTime', FieldType::class, 'DATE_TIME'],
            ['Boolean', FieldType::class, 'BOOLEAN'],
            ['Choice', FieldType::class, 'CHOICE'],
            ['Integer', FieldType::class, 'INTEGER'],
            ['Textarea', FieldType::class, 'TEXTAREA'],
            ['Text', FieldType::class, 'TEXT'],
            ['Components', FieldType::class, 'COMPONENTS'],
        ],
    ]);
};
