<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\ClonePageService;

use Skyeng\MarketingCmsBundle\Domain\Entity\Page;
use Skyeng\MarketingCmsBundle\Domain\Service\ClonePageService\Exception\ClonePageServiceException;

interface ClonePageServiceInterface
{
    /**
     * @throws ClonePageServiceException
     */
    public function clone(Page $page): Page;
}
