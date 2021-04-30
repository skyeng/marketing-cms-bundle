<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\CloneTemplateService;

use Skyeng\MarketingCmsBundle\Domain\Entity\Template;
use Skyeng\MarketingCmsBundle\Domain\Service\CloneTemplateService\Exception\CloneTemplateServiceException;

interface CloneTemplateServiceInterface
{
    /**
     * @throws CloneTemplateServiceException
     */
    public function clone(Template $template): Template;
}
