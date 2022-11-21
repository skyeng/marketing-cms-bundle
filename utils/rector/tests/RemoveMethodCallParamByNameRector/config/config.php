<?php

declare(strict_types=1);

use PHPStan\Type\ArrayType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use Rector\Config\RectorConfig;
use Skyeng\MarketingCmsBundle\Utils\Rector\Rule\RemoveMethodCallParamByNameRector;
use Skyeng\MarketingCmsBundle\Utils\Rector\ValueObject\RemoveMethodCallParamByName;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RemoveMethodCallParamByNameRector::class, [
        new RemoveMethodCallParamByName('A', 'create', new StringType(), 'someVariable'),

        new RemoveMethodCallParamByName('B', 'create', new ObjectType('SomeObject'), 'someObject'),

        new RemoveMethodCallParamByName('C', 'create', new NullType(), 'someNull'),
        new RemoveMethodCallParamByName('C', 'create', new ArrayType(new IntegerType(), new StringType()), 'someArray'),
        new RemoveMethodCallParamByName('C', 'create', new ObjectType('SomeObject'), 'someObject'),
    ]);
};
