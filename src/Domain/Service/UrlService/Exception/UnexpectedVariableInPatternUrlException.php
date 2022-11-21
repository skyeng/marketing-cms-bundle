<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\UrlService\Exception;

use Skyeng\MarketingCmsBundle\Domain\Exception\DomainException;
use Throwable;

class UnexpectedVariableInPatternUrlException extends DomainException
{
    public function __construct(
        string $unexpectedVariable,
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct(
            sprintf('Unexpected variable in patternUrl parameter «%s»', $unexpectedVariable),
            $code,
            $previous
        );
    }
}
