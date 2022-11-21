<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueField extends Constraint
{
    public const NOT_UNIQUE_ERROR = '49e3e453-797d-466e-a39d-61e987e5964c';

    public string $fieldName;

    public ?string $modelName = null;

    public string $message = 'Not unique value';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
