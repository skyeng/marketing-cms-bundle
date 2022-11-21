<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\File\Exception;

use Skyeng\MarketingCmsBundle\Application\Exception\ApplicationException;
use Throwable;

class FileRedirectException extends ApplicationException
{
    public function __construct(
        private string $targetUrl,
        private int $httpCode,
        string $message = '',
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getTargetUrl(): string
    {
        return $this->targetUrl;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }
}
