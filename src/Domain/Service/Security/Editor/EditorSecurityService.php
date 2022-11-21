<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Security\Editor;

use Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Editor\EditorConfigurationInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Security\AuthorizationServiceInterface;
use Skyeng\MarketingCmsBundle\Domain\Service\Security\Exception\AccessDeniedException;

final class EditorSecurityService implements EditorSecurityServiceInterface
{
    public function __construct(
        private EditorConfigurationInterface $editorConfiguration,
        private AuthorizationServiceInterface $authorizationService
    ) {
    }

    public function denyAccessIfRequired(): void
    {
        if (!$this->editorConfiguration->isEnabledSecurity()) {
            return;
        }

        if ($this->editorConfiguration->getSecurityRoles() === []) {
            throw new AccessDeniedException('Access Denied. Make sure editor roles are set in configuration.');
        }

        $this->authorizationService->denyAccessUnlessGranted($this->editorConfiguration->getSecurityRoles());
    }
}
