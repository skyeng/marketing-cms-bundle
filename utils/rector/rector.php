<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Catch_\CatchExceptionNameMatchingTypeRector;
use Rector\CodingStyle\Rector\ClassConst\VarConstantCommentRector;
use Rector\CodingStyle\Rector\ClassMethod\UnSpreadOperatorRector;
use Rector\CodingStyle\Rector\Property\InlineSimplePropertyAnnotationRector;
use Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector;
use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__.'/../../src',
        __DIR__.'/../../tests',
    ]);
    $rectorConfig->skip([
        __DIR__.'/../../tests/_data',
        __DIR__.'/../../tests/_output',
        __DIR__.'/../../tests/_support/_generated',
        CatchExceptionNameMatchingTypeRector::class,
        InlineSimplePropertyAnnotationRector::class,
        UnSpreadOperatorRector::class,
        VarConstantCommentRector::class,
        NewlineAfterStatementRector::class,
        FinalizeClassesWithoutChildrenRector::class,
    ]);

    $rectorConfig->phpVersion(PhpVersion::PHP_80);
    $rectorConfig->parallel(360);

    $rectorConfig->phpstanConfig(__DIR__.'/../phpstan/phpstan.neon');
    $rectorConfig->symfonyContainerPhp(__DIR__.'/symfony-container.php');
    $symfonyContainerXmlPath = __DIR__.'/../symfony-application/var/cache/test/Skyeng_MarketingCmsBundle_Utils_SymfonyApplication_KernelTestDebugContainer.xml';

    if (is_file($symfonyContainerXmlPath)) {
        $rectorConfig->symfonyContainerXml($symfonyContainerXmlPath);
    }

    $rectorConfig->importNames();
    $rectorConfig->importShortClasses(false);

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_80,

        SetList::CODING_STYLE,
        SetList::CODE_QUALITY,
        SetList::TYPE_DECLARATION,
        SetList::TYPE_DECLARATION_STRICT,
        SetList::DEAD_CODE,
        SetList::PRIVATIZATION,

        SymfonySetList::SYMFONY_54,
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::SYMFONY_STRICT,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
        SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES,

        PHPUnitSetList::PHPUNIT_CODE_QUALITY,
        DoctrineSetList::DOCTRINE_CODE_QUALITY,
    ]);
};
