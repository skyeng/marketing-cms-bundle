<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\UseCase\TemplateComponent\Exception;

use Skyeng\MarketingCmsBundle\Domain\Exception\DomainException;

class InvalidParameterException extends DomainException
{
    public function __construct(
        string $message,
        private string $parameterName
    ) {
        parent::__construct($message);
    }

    public function getParameterName(): string
    {
        return $this->parameterName;
    }
}
