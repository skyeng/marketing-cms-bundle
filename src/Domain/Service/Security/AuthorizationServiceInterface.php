<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Security;

use Skyeng\MarketingCmsBundle\Domain\Service\Security\Exception\AccessDeniedException;

interface AuthorizationServiceInterface
{
    /**
     * @param string[] $attributes
     *
     * @throws AccessDeniedException
     */
    public function denyAccessUnlessGranted(array $attributes): void;
}
