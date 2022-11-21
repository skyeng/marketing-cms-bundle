<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Application\Cms\PageComponentType\Service\ComponentTypeResolver\Exception;

use Skyeng\MarketingCmsBundle\Domain\Exception\NotFoundException;

class ComponentTypeNotFoundException extends NotFoundException
{
    protected $message = 'Component type not found';
}
