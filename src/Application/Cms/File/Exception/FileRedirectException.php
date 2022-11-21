<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\File\Exception;

use Skyeng\MarketingCmsBundle\Application\Exception\ApplicationException;
use Throwable;

class FileRedirectException extends ApplicationException
{
    /**
     * @var string
     */
    private $targetUrl;

    /**
     * @var int
     */
    private $httpCode;

    public function __construct(string $targetUrl, int $httpCode, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->targetUrl = $targetUrl;
        $this->httpCode = $httpCode;
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
