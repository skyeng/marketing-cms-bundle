<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Model\Exception;

use Skyeng\MarketingCmsBundle\Domain\Exception\DomainException;

class NotFoundModelConfigException extends DomainException
{
    /**
     * @var string
     */
    protected $message = 'Not found config for model';
}
