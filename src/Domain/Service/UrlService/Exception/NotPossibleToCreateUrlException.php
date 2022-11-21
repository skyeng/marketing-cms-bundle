<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\UrlService\Exception;

use Skyeng\MarketingCmsBundle\Domain\Exception\DomainException;

class NotPossibleToCreateUrlException extends DomainException
{
    /**
     * @var string
     */
    protected $message = 'Trying to create url, but it is not possible';
}
