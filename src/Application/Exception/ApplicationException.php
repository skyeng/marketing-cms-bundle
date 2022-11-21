<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Exception;

use Exception;
use Throwable;

class ApplicationException extends Exception implements ApplicationExceptionInterface
{
    /**
     * @return static
     */
    public static function wrap(Throwable $throwable, ?string $message = null)
    {
        return new static($message ?? $throwable->getMessage(), $throwable->getCode(), $throwable);
    }
}
