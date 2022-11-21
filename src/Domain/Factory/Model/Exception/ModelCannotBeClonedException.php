<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Factory\Model\Exception;

use Skyeng\MarketingCmsBundle\Domain\Exception\DomainException;

class ModelCannotBeClonedException extends DomainException
{
    /**
     * @var string
     */
    protected $message = 'Model cannot be cloned';
}
