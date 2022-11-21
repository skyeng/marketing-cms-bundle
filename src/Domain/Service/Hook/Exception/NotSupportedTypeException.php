<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Hook\Exception;

use Skyeng\MarketingCmsBundle\Domain\Exception\DomainException;

class NotSupportedTypeException extends DomainException
{
    /**
     * @var string
     */
    protected $message = 'Hook not supported this type';
}
