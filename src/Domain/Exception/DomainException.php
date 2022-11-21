<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Exception;

use DomainException as BaseDomainException;

abstract class DomainException extends BaseDomainException implements DomainExceptionInterface
{

}
