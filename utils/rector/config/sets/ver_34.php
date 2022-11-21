<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Removing\Rector\Class_\RemoveInterfacesRector;
use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameClassConstFetch;
use Skyeng\MarketingCmsBundle\Domain\Service\Paginator\PaginatorInterface;
use Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Paginator;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'Skyeng\MarketingCmsBundle\Domain\PaginatorInterface' => 'Skyeng\MarketingCmsBundle\Domain\Service\Paginator\PaginatorInterface',
    ]);

    $rectorConfig->ruleWithConfiguration(RemoveInterfacesRector::class, [
        'Skyeng\MarketingCmsBundle\Domain\Componentable',
    ]);

    $rectorConfig->ruleWithConfiguration(RenameClassConstFetchRector::class, [
        new RenameClassConstFetch('Skyeng\MarketingCmsBundle\Infrastructure\Doctrine\Paginator', 'PAGE_SIZE', 'ITEMS_PER_PAGE'),
    ]);

    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [
        new MethodCallRename(Paginator::class, 'getPageSize', 'getItemsPerPage'),
        new MethodCallRename(PaginatorInterface::class, 'getPageSize', 'getItemsPerPage'),
    ]);
};
