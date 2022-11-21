<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Validator;

use Skyeng\MarketingCmsBundle\Domain\Entity\Model;
use Skyeng\MarketingCmsBundle\Domain\Service\Validator\Exception\ModelNotValidException;

interface ModelValidatorInterface
{
    /**
     * @throws ModelNotValidException
     */
    public function validate(Model $model): void;
}
