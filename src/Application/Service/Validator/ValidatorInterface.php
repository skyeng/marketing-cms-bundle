<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Service\Validator;

use Skyeng\MarketingCmsBundle\Application\Exception\ValidationException;

interface ValidatorInterface
{
    /**
     * @throws ValidationException
     */
    public function validate(string $formClass, mixed $data, ?object $model = null): void;
}
