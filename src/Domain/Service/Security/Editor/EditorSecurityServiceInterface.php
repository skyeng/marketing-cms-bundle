<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Security\Editor;

use Skyeng\MarketingCmsBundle\Domain\Service\Security\Exception\AccessDeniedException;

interface EditorSecurityServiceInterface
{
    /**
     * @throws AccessDeniedException
     */
    public function denyAccessIfRequired(): void;
}
