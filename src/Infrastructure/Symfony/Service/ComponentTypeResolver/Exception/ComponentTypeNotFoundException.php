<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Infrastructure\Symfony\Service\ComponentTypeResolver\Exception;

use Skyeng\MarketingCmsBundle\Domain\Exception\NotFoundException;

class ComponentTypeNotFoundException extends NotFoundException
{
    protected $message = 'Component type not found';
}
