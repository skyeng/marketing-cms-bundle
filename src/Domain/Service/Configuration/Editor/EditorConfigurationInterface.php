<?php

declare(strict_types=1);

namespace Skyeng\MarketingCmsBundle\Domain\Service\Configuration\Editor;

interface EditorConfigurationInterface
{
    public function getShowEditorLink(): bool;

    public function isEnabledSecurity(): bool;

    /**
     * @return string[]
     */
    public function getSecurityRoles(): array;
}
