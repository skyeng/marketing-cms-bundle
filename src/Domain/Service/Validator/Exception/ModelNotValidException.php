<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Validator\Exception;

use Skyeng\MarketingCmsBundle\Domain\Exception\DomainException;
use Throwable;

class ModelNotValidException extends DomainException
{
    /**
     * @param mixed[] $errors
     */
    public function __construct(
        string $message = 'Model not valid',
        int $code = 400,
        ?Throwable $previous = null,
        /** @var mixed[] */
        protected array $errors = []
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return mixed[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
