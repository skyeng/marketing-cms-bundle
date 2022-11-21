<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Exception;

use InvalidArgumentException;
use Throwable;

class ValidationException extends InvalidArgumentException implements ApplicationExceptionInterface
{
    /**
     * @param mixed[] $errors
     */
    public function __construct(
        string $message = '',
        int $code = 400,
        ?Throwable $previous = null,
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

    public function getErrorsAsString(): string
    {
        $result = '';

        foreach ($this->errors as $key => $values) {
            $result .= sprintf('[%s: %s]', $key, implode(' ', $values));
        }

        return $result;
    }

    public static function fromErrors(array $errors): self
    {
        return new self('Validation error', 400, null, $errors);
    }
}
