<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Locale\Exception;

use Skyeng\MarketingCmsBundle\Domain\Exception\DomainException;

class IncorrectDefaultLocaleException extends DomainException
{
    /**
     * @var string
     */
    protected $message = 'Incorrect Default Locale value';
}
