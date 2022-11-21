<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Utils\Rector\ValueObject;

use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

class RemoveMethodCallParamByName
{
    private string $class;
    private string $methodName;
    private Type $argumentType;
    private string $argumentName;

    public function __construct(
        string $class,
        string $methodName,
        Type $argumentType,
        string $argumentName
    ) {
        $this->class = $class;
        $this->methodName = $methodName;
        $this->argumentType = $argumentType;
        $this->argumentName = $argumentName;
    }

    public function getObjectType(): ObjectType
    {
        return new ObjectType($this->class);
    }

    public function getMethodName(): string
    {
        return $this->methodName;
    }

    public function getArgumentType(): Type
    {
        return $this->argumentType;
    }

    public function getArgumentName(): string
    {
        return $this->argumentName;
    }
}
