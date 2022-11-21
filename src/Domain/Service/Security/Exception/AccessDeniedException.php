<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Security\Exception;

use Skyeng\MarketingCmsBundle\Domain\Exception\DomainException;

final class AccessDeniedException extends DomainException
{
    /**
     * @var string
     */
    protected $message = 'Access denied.';
}
